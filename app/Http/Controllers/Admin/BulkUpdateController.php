<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserCommodity;
use App\Models\CommodityTransaction;
use App\Models\Electronics;
use App\Models\Loan;
use App\Models\LoanPayment;

use App\Models\MonthlyUpload;
use App\Models\SavingTransaction;
use App\Models\ShareTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Services\FinancialCalculationService;

class BulkUpdateController extends Controller
{
    /**
     * Display the bulk update form
     */
    public function index()
    {
        // Get recent monthly uploads for display
        $recentUploads = MonthlyUpload::with('uploader')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->take(10)
            ->get();

        // Compute next allowed month (sequential rule based on last successful monthly_contributions upload)
        $latestCompleted = MonthlyUpload::where('status', 'completed')
            ->where('upload_type', 'monthly_contributions')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
        $nextAllowed = $latestCompleted
            ? Carbon::create($latestCompleted->year, $latestCompleted->month, 1)->addMonth()->format('F Y')
            : null;

        return view('admin.bulk_updates', compact('recentUploads', 'nextAllowed'));
    }

    /**
     * Process the uploaded file and show preview
     */
    public function upload(Request $request)
    {
        Log::info('Bulk upload started', [
            'user_id' => Auth::id(),
            'file_name' => $request->file('excel_file')->getClientOriginalName(),
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'upload_type' => $request->upload_type
        ]);

        try {
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv',
                'transaction_date' => 'required|date',
                'description' => 'required|string|max:255',
                'update_fields' => 'required|array',
                'missing_data' => 'required|in:skip,zero',
                'upload_type' => 'required|in:monthly_contributions,cumulative_balances',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            throw $e;
        }

        // Enforce month sequencing and handle re-uploads
        $transactionDate = Carbon::parse($request->transaction_date);
        $year = $transactionDate->year;
        $month = $transactionDate->month;
        $isCumulative = $request->upload_type === 'cumulative_balances';

        // Check if an upload record already exists for this month
        // For monthly_contributions, we block duplicates if completed.
        // For cumulative_balances, we allow multiple (they reconcile state).
        $existingUpload = MonthlyUpload::where('year', $year)
            ->where('month', $month)
            ->where('upload_type', $request->upload_type)
            ->first();

        if (!$isCumulative) {
            // Sequential rule: only for monthly_contributions
            $latestCompleted = MonthlyUpload::where('status', 'completed')
                ->where('upload_type', 'monthly_contributions')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->first();

            if ($latestCompleted) {
                $next = Carbon::create($latestCompleted->year, $latestCompleted->month, 1)->addMonth();
                if ($transactionDate->greaterThan($next->copy()->startOfMonth())) {
                    return redirect()->route('admin.bulk_updates')
                        ->with('error', 'Invalid month selected. You cannot jump months. Next allowed month is ' . $next->format('F Y') . '.');
                }
                if ($transactionDate->lessThan($next->copy()->startOfMonth())) {
                    return redirect()->route('admin.bulk_updates')
                        ->with('error', 'Invalid month selected. You cannot upload for a previous month. Next allowed month is ' . $next->format('F Y') . '.');
                }
            }

            // Block duplicates for monthly_contributions
            if ($existingUpload && $existingUpload->status === 'completed') {
                return redirect()->route('admin.bulk_updates')
                    ->with('error', "Financial records for {$transactionDate->format('F Y')} have already been uploaded.");
            }
        }

        // If an upload exists but is failed/reversed, allow re-upload and remember the record to reuse during processing
        $reuploadId = ($existingUpload && in_array($existingUpload->status, ['failed', 'reversed'])) ? $existingUpload->id : null;

        // Store the file temporarily
        $file = $request->file('excel_file');
        $fileName = $file->getClientOriginalName();
        $filePath = $file->storeAs('temp', Str::random(40) . '.' . $file->getClientOriginalExtension());
        $fullPath = Storage::path($filePath);

        Log::info('File stored temporarily', [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'full_path' => $fullPath,
            'file_exists' => file_exists($fullPath),
            'file_size' => $file->getSize()
        ]);

        // Verify file exists before processing
        if (!file_exists($fullPath)) {
            Log::error('Uploaded file not found', [
                'file_path' => $filePath,
                'full_path' => $fullPath
            ]);
            return redirect()->route('admin.bulk_updates')
                ->with('error', 'Uploaded file could not be found. Please try again.');
        }
        $rows = [];

        // Check file extension to determine how to parse it
        $extension = strtolower($file->getClientOriginalExtension());

        try {
            if ($extension === 'csv') {
                // Read CSV file content
                $fileContent = file_get_contents($fullPath);
                $lines = explode("\n", $fileContent);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        $rows[] = str_getcsv($line);
                    }
                }
            } else {
                // Handle Excel files (.xlsx, .xls) using PhpSpreadsheet
                $spreadsheet = IOFactory::load($fullPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = [];
                    for ($col = 'A'; $col <= $highestColumn; $col++) {
                        $cellValue = $worksheet->getCell($col . $row)->getCalculatedValue();
                        $rowData[] = $cellValue;
                    }
                    $rows[] = $rowData;
                }
            }
        } catch (\Exception $e) {
            Log::error('File processing error', [
                'error' => $e->getMessage(),
                'file' => $fileName,
                'line' => $e->getLine()
            ]);

            return redirect()->route('admin.bulk_updates')
                ->with('error', 'Error reading file: ' . $e->getMessage())
                ->withInput();
        }

        // Get headers - Detect which row contains the actual headers (A spreadsheet might have titles in row 1)
        $headerRowIndex = 0;
        $foundHeader = false;
        
        for ($i = 0; $i < min(count($rows), 5); $i++) {
            $row = $rows[$i];
            $rowStr = strtolower(implode(' ', array_map(function($v) { return (string)$v; }, $row)));
            
            // Look for distinctive identifiers that point to this being the header row
            if (str_contains($rowStr, 'coopno') || str_contains($rowStr, 'coop-no') || 
                str_contains($rowStr, 'member') || str_contains($rowStr, 'surname') || 
                str_contains($rowStr, 'shares')) {
                $headerRowIndex = $i;
                $foundHeader = true;
                break;
            }
        }
        
        if ($foundHeader) {
            $headers = $rows[$headerRowIndex];
            $rows = array_slice($rows, $headerRowIndex + 1);
            Log::info("Header row detected at index $headerRowIndex", ['headers' => $headers]);
        } else {
            // Re-shift headers if no better row found
            $headers = array_shift($rows);
            // $rows is already shifted by array_shift
            Log::warning("No distinct header row detected, sticking with row 1.");
        }

        // Map column indices
        $columnMap = $this->mapColumns($headers);

        // Process data and validate with detailed error tracking
        $previewData = [];
        $notFoundCount = 0;
        $invalidDataCount = 0;
        $validRecords = 0;
        $duplicateCount = 0;

        // Track detailed validation issues
        $validationIssues = [
            'not_found' => [],
            'invalid_data' => [],
            'duplicates' => [],
            'missing_required' => [],
            'valid_records' => []
        ];

        $seenMemberNumbers = [];

        foreach ($rows as $index => $row) {
            // Skip rows with insufficient columns
            if (count($row) < 3) {
                continue;
            }

            // Get COOPNO (skip empty rows)
            $coopnoIndex = $columnMap['coopno'] ?? 1;
            if (!isset($row[$coopnoIndex]) || empty($row[$coopnoIndex])) {
                $validationIssues['missing_required'][] = [
                    'row' => $index + 2, // Excel row number (accounting for header)
                    'issue' => 'Missing member number',
                    'data' => $row
                ];
                continue;
            }

            $coopno = trim($row[$coopnoIndex]);

            // Check for duplicates within the file
            if (isset($seenMemberNumbers[$coopno])) {
                $duplicateCount++;
                $validationIssues['duplicates'][] = [
                    'member_number' => $coopno,
                    'first_row' => $seenMemberNumbers[$coopno],
                    'duplicate_row' => $index + 2,
                    'data' => $row
                ];
                continue;
            }
            $seenMemberNumbers[$coopno] = $index + 2;

            // Check if the member exists in the database
            $member = Member::where('member_number', $coopno)->first();
            $memberExists = !is_null($member);

            if (!$memberExists) {
                $notFoundCount++;
                $validationIssues['not_found'][] = [
                    'row' => $index + 2,
                    'member_number' => $coopno,
                    'surname' => $row[$columnMap['surname'] ?? 2] ?? '',
                    'othernames' => $row[$columnMap['othernames'] ?? 3] ?? ''
                ];
            }

            // Check for invalid data formats in financial fields
            $hasInvalidData = false;
            $dataErrors = [];
            $financialFields = ['shares', 'savings', 'loan_repay', 'loan_int', 'essential', 'non_essential', 'electronics'];

            foreach ($financialFields as $field) {
                $fieldIndex = $columnMap[$field] ?? null;
                if ($fieldIndex !== null && isset($row[$fieldIndex])) {
                    $value = $row[$fieldIndex];

                    // Skip empty values (they're valid as 0)
                    if ($value === null || $value === '') {
                        continue;
                    }

                    // Check if value is numeric
                    if (!is_numeric($value)) {
                        $hasInvalidData = true;
                        $dataErrors[] = "$field contains non-numeric data: '$value'";
                    }

                    // Check for negative values
                    elseif (is_numeric($value) && $value < 0) {
                        $hasInvalidData = true;
                        $dataErrors[] = "$field has negative value: $value";
                    }

                    // Check for extremely large values (potential data entry error)
                    elseif (is_numeric($value) && $value > 10000000) {
                        $hasInvalidData = true;
                        $dataErrors[] = "$field has unusually large value: $value";
                    }
                }
            }

            // Validate entrance field separately (should be Yes/No or boolean)
            $entranceIndex = $columnMap['entrance'] ?? null;
            if ($entranceIndex !== null && isset($row[$entranceIndex])) {
                $entranceValue = trim($row[$entranceIndex]);
                if (!empty($entranceValue)) {
                    $validEntranceValues = ['yes', 'no', '1', '0', 'true', 'false', 'y', 'n'];
                    if (!in_array(strtolower($entranceValue), $validEntranceValues)) {
                        $hasInvalidData = true;
                        $dataErrors[] = "entrance contains invalid value: '$entranceValue' (should be Yes/No, 1/0, or True/False)";
                    }
                }
            }

            if ($hasInvalidData) {
                $invalidDataCount++;
                $validationIssues['invalid_data'][] = [
                    'row' => $index + 2,
                    'member_number' => $coopno,
                    'surname' => $row[$columnMap['surname'] ?? 2] ?? '',
                    'errors' => $dataErrors
                ];
            }

            $status = 'valid';
            if (!$memberExists) {
                $status = 'not_found';
            } elseif ($hasInvalidData) {
                $status = 'invalid_data';
            } else {
                $validRecords++;

                // Collect valid record data for display
                $validationIssues['valid_records'][] = [
                    'row' => $index + 2,
                    'member_number' => $coopno,
                    'name' => trim(($row[$columnMap['surname'] ?? null] ?? '') . ' ' . ($row[$columnMap['othernames'] ?? null] ?? '')),
                    'entrance' => $row[$columnMap['entrance'] ?? null] ?? '',
                    'shares' => floatval($row[$columnMap['shares'] ?? null] ?? 0),
                    'savings' => floatval($row[$columnMap['savings'] ?? null] ?? 0),
                    'loan_repay' => floatval($row[$columnMap['loan_repay'] ?? null] ?? 0),
                    'loan_int' => floatval($row[$columnMap['loan_int'] ?? null] ?? 0),
                    'essential' => floatval($row[$columnMap['essential'] ?? null] ?? 0),
                    'non_essential' => floatval($row[$columnMap['non_essential'] ?? null] ?? 0),
                    'electronics' => floatval($row[$columnMap['electronics'] ?? null] ?? 0),
                    'total' => floatval($row[$columnMap['total'] ?? null] ?? 0)
                ];
            }

            // Get indices (with defaults if mapping fails)
            $snoIndex = $columnMap['sno'] ?? 0;
            $surnameIndex = $columnMap['surname'] ?? 2;
            $othernamesIndex = $columnMap['othernames'] ?? 3;
            $entranceIndex = $columnMap['entrance'] ?? 4;
            $sharesIndex = $columnMap['shares'] ?? 5;
            $savingsIndex = $columnMap['savings'] ?? 6;
            $loanRepayIndex = $columnMap['loan_repay'] ?? 7;
            $loanIntIndex = $columnMap['loan_int'] ?? 8;
            $essentialIndex = $columnMap['essential'] ?? 9;
            $nonEssentialIndex = $columnMap['non_essential'] ?? 10;
            $electronicsIndex = $columnMap['electronics'] ?? 11;
            $totalIndex = $columnMap['total'] ?? 12;

            // Format the data for preview
            $previewData[] = [
                'sno' => isset($row[$snoIndex]) ? $row[$snoIndex] : ($index + 1),
                'coopno' => $coopno,
                'name' => (isset($row[$surnameIndex]) ? $row[$surnameIndex] : '') . ' ' .
                          (isset($row[$othernamesIndex]) ? $row[$othernamesIndex] : ''),
                'entrance' => isset($row[$entranceIndex]) ? trim($row[$entranceIndex]) : '-',
                'shares' => $this->formatAmount(isset($row[$sharesIndex]) ? $row[$sharesIndex] : null),
                'savings' => $this->formatAmount(isset($row[$savingsIndex]) ? $row[$savingsIndex] : null),
                'loan_repay' => $this->formatAmount(isset($row[$loanRepayIndex]) ? $row[$loanRepayIndex] : null),
                'loan_int' => $this->formatAmount(isset($row[$loanIntIndex]) ? $row[$loanIntIndex] : null),
                'essential' => $this->formatAmount(isset($row[$essentialIndex]) ? $row[$essentialIndex] : null),
                'non_essential' => $this->formatAmount(isset($row[$nonEssentialIndex]) ? $row[$nonEssentialIndex] : null),
                'electronics' => $this->formatAmount(isset($row[$electronicsIndex]) ? $row[$electronicsIndex] : null),
                'total' => $this->formatAmount(isset($row[$totalIndex]) ? $row[$totalIndex] : null),
                'status' => $status
            ];
        }

        // Store only essential data in session to avoid max_allowed_packet error
        $sessionId = Str::random(40);

        // Store large data in temporary files
        $previewFile = 'temp/preview_' . $sessionId . '.json';
        Storage::put($previewFile, json_encode(array_slice($previewData, 0, 50))); // Only store first 50 for preview

        Session::put('bulk_update_' . $sessionId, [
            'file_path' => $fullPath,
            'file_name' => $fileName,
            'total_rows' => count($previewData),
            'column_map' => $columnMap,
            'transaction_date' => $request->transaction_date,
            'description' => $request->description,
            'update_fields' => $request->update_fields,
            'missing_data' => $request->missing_data,
            'upload_type' => $request->upload_type,
            'existing_upload_id' => isset($reuploadId) ? $reuploadId : null,
            'preview_file' => $previewFile,
            'validation_summary' => [
                'valid_records' => $validRecords,
                'not_found_count' => $notFoundCount,
                'invalid_data_count' => $invalidDataCount,
                'duplicate_count' => $duplicateCount,
                'missing_required_count' => count($validationIssues['missing_required'])
            ]
        ]);

        // Store validation issues in a separate file to avoid session size limits
        $validationFile = 'temp/validation_' . $sessionId . '.json';
        Storage::put($validationFile, json_encode($validationIssues));

        // If there are validation issues, show detailed error page with actionable solutions
        if ($notFoundCount > 0 || $invalidDataCount > 0 || $duplicateCount > 0 || !empty($validationIssues['missing_required'])) {
            return view('admin.bulk_updates_validation_errors', [
                'fileName' => $fileName,
                'totalRecords' => count($previewData),
                'validRecords' => $validRecords,
                'notFoundCount' => $notFoundCount,
                'invalidDataCount' => $invalidDataCount,
                'duplicateCount' => $duplicateCount,
                'missingRequiredCount' => count($validationIssues['missing_required']),
                'validationIssues' => $validationIssues,
                'transactionDate' => Carbon::parse($request->transaction_date)->format('F j, Y'),
                'description' => $request->description,
                'updateFields' => $request->update_fields,
                'missingDataHandling' => $request->missing_data,
                'sessionId' => $sessionId
            ]);
        }

        return view('admin.bulk_updates_preview', [
            'fileName' => $fileName,
            'totalRecords' => count($previewData),
            'hasErrors' => false,
            'notFoundCount' => $notFoundCount,
            'invalidDataCount' => $invalidDataCount,
            'validRecords' => $validRecords,
            'previewData' => $previewData,
            'transactionDate' => Carbon::parse($request->transaction_date)->format('F j, Y'),
            'description' => $request->description,
            'updateFields' => $request->update_fields,
            'missingDataHandling' => $request->missing_data,
            'sessionId' => $sessionId
        ]);
    }

    /**
     * Process the validated data and update member records
     */
    public function process(Request $request)
    {
        $sessionId = $request->session_id;
        $sessionData = Session::get('bulk_update_' . $sessionId);
        $processValidOnly = $request->boolean('process_valid_only', false);

        if (!$sessionData) {
            return redirect()->route('admin.bulk_updates')
                ->with('error', 'Session expired. Please upload the file again.');
        }

        // Re-read the Excel file to process data
        $filePath = $sessionData['file_path'];
        if (!file_exists($filePath)) {
            return redirect()->route('admin.bulk_updates')
                ->with('error', 'Upload file not found. Please upload again.');
        }

        Log::info('Processing bulk upload', [
            'session_id' => $sessionId,
            'process_valid_only' => $processValidOnly,
            'file_path' => $filePath
        ]);

        // Create or reuse monthly upload record
        $transactionDate = Carbon::parse($sessionData['transaction_date']);

        $monthlyUpload = null;
        if (!empty($sessionData['existing_upload_id'])) {
            $monthlyUpload = MonthlyUpload::find($sessionData['existing_upload_id']);
        }

        if ($monthlyUpload) {
            // Reuse existing failed/reversed record for this month
            $monthlyUpload->update([
                'upload_type' => $sessionData['upload_type'] ?? 'monthly_contributions',
                'file_name' => $sessionData['file_name'],
                'file_path' => $sessionData['file_path'],
                'total_records' => $sessionData['total_rows'],
                'processed_records' => 0,
                'failed_records' => 0,
                'update_fields' => $sessionData['update_fields'],
                'description' => $sessionData['description'],
                'uploaded_by' => Auth::id(),
                'error_message' => null,
                'processing_summary' => null,
            ]);
            $monthlyUpload->markAsStarted();
        } else {
            $monthlyUpload = MonthlyUpload::create([
                'year' => $transactionDate->year,
                'month' => $transactionDate->month,
                'upload_type' => $sessionData['upload_type'] ?? 'monthly_contributions',
                'file_name' => $sessionData['file_name'],
                'file_path' => $sessionData['file_path'],
                'total_records' => $sessionData['total_rows'],
                'processed_records' => 0,
                'failed_records' => 0,
                'update_fields' => $sessionData['update_fields'],
                'description' => $sessionData['description'],
                'uploaded_by' => Auth::id(),
            ]);
            $monthlyUpload->markAsStarted();
        }

        // Re-process the Excel file for actual data processing
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $rows = [];

        try {
            if ($extension === 'csv') {
                $fileContent = file_get_contents($filePath);
                $lines = explode("\n", $fileContent);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        $rows[] = str_getcsv($line);
                    }
                }
            } else {
                // Excel file - Use robust reading logic
                $spreadsheet = IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                for ($row = 1; $row <= $highestRow; $row++) {
                    $rowData = [];
                    for ($col = 'A'; $col <= $highestColumn; $col++) {
                        $cellValue = $worksheet->getCell($col . $row)->getCalculatedValue();
                        $rowData[] = $cellValue;
                    }
                    $rows[] = $rowData;
                }
            }
        } catch (\Exception $e) {
            Log::error('Process: File reading error', ['error' => $e->getMessage()]);
            return redirect()->route('admin.bulk_updates')->with('error', 'Error reading file during processing: ' . $e->getMessage());
        }

        // Detect header row to ensure dataRows starts at the correct index
        // This must match the logic used in upload()
        $headerRowIndex = -1;
        for ($i = 0; $i < min(count($rows), 5); $i++) {
            $rowStr = strtolower(implode(' ', array_map(function($v) { return (string)$v; }, $rows[$i])));
            if (str_contains($rowStr, 'coopno') || str_contains($rowStr, 'member') || str_contains($rowStr, 'shares')) {
                $headerRowIndex = $i;
                break;
            }
        }

        // Skip everything up to and including the header row
        $dataRows = array_slice($rows, ($headerRowIndex >= 0 ? $headerRowIndex + 1 : 1));
        
        Log::info('Data rows extracted for processing', [
            'header_index' => $headerRowIndex,
            'total_data_rows' => count($dataRows)
        ]);
        $validRecords = [];
        $processedCount = 0;
        $errorCount = 0;
        $columnMap = $sessionData['column_map'];

        // Re-validate and collect valid records for processing
        foreach ($dataRows as $index => $row) {
            if (empty($row) || count($row) < 3) continue;

            $coopno = trim($row[$columnMap['coopno'] ?? 1] ?? '');
            if (empty($coopno)) continue;

            // Check if member exists
            $memberExists = User::whereHas('member', function($query) use ($coopno) {
                $query->where('member_number', $coopno);
            })->exists();

            if ($memberExists) {
                $recordData = [
                    'coopno' => $coopno,
                    'surname' => trim($row[$columnMap['surname'] ?? null] ?? ''),
                    'othernames' => trim($row[$columnMap['othernames'] ?? null] ?? ''),
                ];

                foreach (['entrance', 'shares', 'savings', 'loan_repay', 'loan_int', 'essential', 'non_essential', 'electronics'] as $f) {
                    $idx = $columnMap[$f] ?? null;
                    $recordData[$f] = ($idx !== null && isset($row[$idx])) ? $row[$idx] : null;
                }
                $validRecords[] = $recordData;
            } else {
                Log::warning("Skipping row in process(): Member not found for COOPNO: $coopno");
            }
        }

        // Use transactions for database updates
        DB::beginTransaction();

        try {
            $updateFields = $sessionData['update_fields'];
            $transactionDate = Carbon::parse($sessionData['transaction_date']);
            $description = $sessionData['description'];
            $missingData = $sessionData['missing_data'];
            $successCount = 0;
            $errorCount = 0;

            foreach ($validRecords as $record) {
                // Find the user by cooperative number
                $user = User::whereHas('member', function($query) use ($record) {
                    $query->where('member_number', $record['coopno']);
                })->first();

                if (!$user) {
                    Log::warning("User not found for member number: " . $record['coopno']);
                    $errorCount++;
                    continue; // Skip if user not found
                }

                Log::info("Processing member: " . $record['coopno'] . " - " . $user->name);

                // PRE-CHECK: Share Limit Rule
                // For monthly contributions, the contribution itself cannot exceed 10k
                // For cumulative balances, this limit does NOT apply as it represents the total balance
                if (in_array('shares', $updateFields)) {
                    $rawShareValue = $record['shares'];
                    if ($rawShareValue !== null && $rawShareValue !== '') {
                        $isMonthlyContribution = $this->isMonthlyContribution($sessionData, 'shares');
                        
                        if ($isMonthlyContribution) {
                            $shareAmountValue = $this->parseNumericAmount($rawShareValue);
                            $maxShareContribution = config('business_rules.shares.maximum_contribution', 10000);
                            
                            if ($shareAmountValue > $maxShareContribution) {
                                Log::warning("MAB Upload: Member {$record['coopno']} monthly share contribution ({$shareAmountValue}) exceeds limit of {$maxShareContribution}. Skipping ENTIRE row.");
                                $errorCount++;
                                continue; 
                            }
                        }
                    }
                }

                // Process each update field
                foreach ($updateFields as $field) {
                    try {
                    if ($field !== 'entrance') {
                        $amount = $this->parseNumericAmount($record[$field]);

                        // Skip if amount is zero and missing_data is set to 'skip'
                        // BUT: For cumulative balances, 0 is a valid target (means fully paid/cleared)
                        $isCumulativeMode = !$this->isMonthlyContribution($sessionData, $field);
                        if ($amount == 0 && $missingData == 'skip' && !$isCumulativeMode) {
                            continue;
                        }
                    } else {
                        $amount = 0; // Not used for entrance numeric logic
                    }

                    switch ($field) {
                        case 'entrance':
                            // Process entrance fee status (Yes/No flag)
                            $entranceValue = trim($record['entrance'] ?? '');
                            
                            // BUSINESS RULE: If the field is empty in the spreadsheet, do NOT change the status
                            if ($entranceValue === '') {
                                Log::info("Skipping entrance processing for user {$user->id}: cell is empty.");
                                break;
                            }

                            $entrancePaid = $this->convertToBoolean($entranceValue);
                            Log::info("Processing entrance for user {$user->id}: raw value = '{$entranceValue}', converted to boolean = {$entrancePaid}, currently paid = {$user->member->entrance_fee_paid}");
                            if ($entrancePaid && !$user->member->entrance_fee_paid) {
                                // Mark entrance fee as paid
                                $user->member->update(['entrance_fee_paid' => true]);

                                // Create a transaction record for reversibility
                                Transaction::create([
                                    'user_id' => $user->id,
                                    'type' => 'entrance_fee',
                                    'amount' => 0,
                                    'description' => $description . ' - Entrance Fee Paid',
                                    'reference' => 'MAB-ENT-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                                    'status' => 'completed',
                                ]);

                                Log::info("Marked entrance fee as paid for user {$user->id}");
                            } elseif (!$entrancePaid && $user->member->entrance_fee_paid) {
                                // Optionally mark as unpaid if needed
                                $user->member->update(['entrance_fee_paid' => false]);
                                Log::info("Marked entrance fee as unpaid for user {$user->id}");
                            }
                            break;

                        case 'shares':
                            // Update shares
                            $isMonthlyContribution = $this->isMonthlyContribution($sessionData, 'shares');

                            if ($isMonthlyContribution) {
                                if ($amount > 0) {
                                    // This is a monthly contribution - add to existing balance
                                    ShareTransaction::create([
                                        'user_id' => $user->id,
                                        'amount' => $amount,
                                        'type' => 'credit',
                                        'description' => $description,
                                        'transaction_date' => $transactionDate,
                                    ]);

                                    $user->member->increment('total_share_amount', $amount);
                                }
                            } else {
                                // This is a cumulative balance - set the total balance
                                $currentShares = FinancialCalculationService::calculateSharesBalance($user);
                                // Calculate the difference for the transaction
                                $difference = $amount - $currentShares;

                                if ($difference !== 0.0) {
                                    ShareTransaction::create([
                                        'user_id' => $user->id,
                                        'amount' => abs($difference),
                                        'type' => $difference > 0 ? 'credit' : 'debit',
                                        'description' => $description . ' (Balance Reconciliation)',
                                        'transaction_date' => $transactionDate,
                                    ]);

                                    $user->member->update(['total_share_amount' => $amount]);
                                    Log::info("Reconciled shares for user {$user->id}: from {$currentShares} to {$amount} (" . ($difference > 0 ? 'credited' : 'debit') . " {$difference})");
                                }
                            }
                            break;

                        case 'savings':
                            // Update savings
                            $isMonthlyContribution = $this->isMonthlyContribution($sessionData, 'savings');

                            if ($isMonthlyContribution) {
                                if ($amount > 0) {
                                    // This is a monthly contribution - add to existing balance
                                    SavingTransaction::create([
                                        'user_id' => $user->id,
                                        'amount' => $amount,
                                        'type' => 'credit',
                                        'description' => $description,
                                        'transaction_date' => $transactionDate,
                                    ]);

                                    $user->member->increment('total_saving_amount', $amount);
                                }
                            } else {
                                // This is a cumulative balance - set the total balance
                                $currentSavings = FinancialCalculationService::calculateSavingsBalance($user);
                                $difference = $amount - $currentSavings;

                                if ($difference !== 0.0) {
                                    SavingTransaction::create([
                                        'user_id' => $user->id,
                                        'amount' => abs($difference),
                                        'type' => $difference > 0 ? 'credit' : 'debit',
                                        'description' => $description . ' (Balance Reconciliation)',
                                        'transaction_date' => $transactionDate,
                                    ]);

                                    $user->member->update(['total_saving_amount' => $amount]);
                                    Log::info("Reconciled savings for user {$user->id}: from {$currentSavings} to {$amount} (" . ($difference > 0 ? 'credited' : 'debit') . " {$difference})");
                                }
                            }
                            break;


                        case 'loan_repay':
                            Log::info("Processing loan_repay for user {$user->id}: amount = {$amount}");
                            $isMonthlyContribution = $this->isMonthlyContribution($sessionData, 'loan_repay');
                            Log::info("Is monthly contribution: " . ($isMonthlyContribution ? 'YES' : 'NO (cumulative)'));

                            if ($isMonthlyContribution) {
                                if ($amount > 0) {
                                    $this->processCascadingLoanRepaymentMAB($user, $amount, $description, $transactionDate);
                                    Log::info("Processed loan principal repayment for user {$user->id}: {$amount}");
                                }
                            } else {
                                // Cumulative mode: $amount is the TARGET principal balance
                                Log::info("Calling reconcileLoanPrincipalBalanceMAB for user {$user->id} with target: {$amount}");
                                $this->reconcileLoanPrincipalBalanceMAB($user, $amount, $description, $transactionDate);
                            }
                            break;

                        case 'loan_int':
                            Log::info("Processing loan_int for user {$user->id}: amount = {$amount}");
                            $isMonthlyContribution = $this->isMonthlyContribution($sessionData, 'loan_int');
                            Log::info("Is monthly contribution: " . ($isMonthlyContribution ? 'YES' : 'NO (cumulative)'));

                            if ($isMonthlyContribution) {
                                if ($amount > 0) {
                                    // Find active loans for this user
                                    $activeLoan = Loan::where('user_id', $user->id)
                                        ->where('status', 'active')
                                        ->orderBy('created_at', 'desc')
                                        ->first();

                                    if ($activeLoan) {
                                        // Create loan payment record for interest payment
                                        LoanPayment::create([
                                            'loan_id' => $activeLoan->id,
                                            'amount' => $amount,
                                            'payment_date' => $transactionDate,
                                            'due_date' => $transactionDate,
                                            'status' => 'paid',
                                            'payment_method' => 'deduction',
                                            'notes' => $description . ' - Interest Payment'
                                        ]);

                                        Log::info("Processed loan interest payment for user {$user->id}: {$amount}");
                                    } else {
                                        Log::warning("Loan interest payment attempted for user {$user->id} but no active loan found. Amount: {$amount}");
                                    }
                                }
                            } else {
                                // Cumulative mode: $amount is the TARGET interest balance (outstanding interest)
                                Log::info("Calling reconcileLoanInterestBalanceMAB for user {$user->id} with target: {$amount}");
                                $this->reconcileLoanInterestBalanceMAB($user, $amount, $description, $transactionDate);
                            }
                            break;

                        case 'essential':
                        case 'non_essential':
                            // CORRECTED: Process commodity repayments (SUBTRACTION operation)
                            // Rationale: Members collect goods on credit and repay with money through MAB deductions
                            $isMonthlyContribution = $this->isMonthlyContribution($sessionData, $field);

                            if ($isMonthlyContribution) {
                                if ($amount > 0) {
                                    // Create commodity transaction record as DEBIT (repayment)
                                    CommodityTransaction::create([
                                        'user_id' => $user->id,
                                        'commodity_type' => $field, // 'essential' or 'non_essential'
                                        'amount' => $amount,
                                        'type' => 'debit',
                                        'description' => $description . ' - ' . ucfirst(str_replace('_', ' ', $field)) . ' Repayment',
                                        'transaction_date' => $transactionDate,
                                        'processed_by' => Auth::id(),
                                    ]);

                                    // Also add a general transaction entry
                                    Transaction::create([
                                        'user_id' => $user->id,
                                        'type' => 'commodity_repayment',
                                        'amount' => $amount,
                                        'description' => $description . ' - ' . ucfirst(str_replace('_', ' ', $field)) . ' Repayment',
                                        'reference' => 'MAB-COM-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . Str::random(6),
                                        'status' => 'completed',
                                    ]);

                                    $userCommodity = UserCommodity::where('user_id', $user->id)
                                        ->where('commodity_name', $field)
                                        ->first();

                                    if ($userCommodity) {
                                        $userCommodity->balance = max(0, ($userCommodity->balance ?? 0) - $amount);
                                        $userCommodity->save();
                                    } else {
                                        UserCommodity::create([
                                            'user_id' => $user->id,
                                            'commodity_name' => $field,
                                            'balance' => -$amount
                                        ]);
                                    }
                                }
                            } else {
                                // Cumulative mode: $amount is the TARGET balance
                                $userCommodity = UserCommodity::where('user_id', $user->id)
                                    ->where('commodity_name', $field)
                                    ->first();
                                
                                $currentBalance = $userCommodity ? ($userCommodity->balance ?? 0) : 0;
                                $difference = $amount - $currentBalance;

                                if ($difference !== 0.0) {
                                    // If difference > 0, it's a purchase (liability increased)
                                    // If difference < 0, it's a repayment (liability decreased)
                                    CommodityTransaction::create([
                                        'user_id' => $user->id,
                                        'commodity_type' => $field,
                                        'amount' => abs($difference),
                                        'type' => $difference > 0 ? 'credit' : 'debit',
                                        'description' => $description . ' (Balance Reconciliation)',
                                        'transaction_date' => $transactionDate,
                                        'processed_by' => Auth::id(),
                                    ]);

                                    Transaction::create([
                                        'user_id' => $user->id,
                                        'type' => $difference > 0 ? 'commodity_purchase' : 'commodity_repayment',
                                        'amount' => abs($difference),
                                        'description' => $description . ' (Balance Reconciliation)',
                                        'reference' => 'MAB-COM-REC-' . date('Ymd') . '-' . Str::random(6),
                                        'status' => 'completed',
                                    ]);

                                    if ($userCommodity) {
                                        $userCommodity->update(['balance' => $amount]);
                                    } else {
                                        UserCommodity::create([
                                            'user_id' => $user->id,
                                            'commodity_name' => $field,
                                            'balance' => $amount
                                        ]);
                                    }
                                    Log::info("Reconciled commodity {$field} for user {$user->id}: from {$currentBalance} to {$amount}");
                                }
                            }
                            break;

                        case 'electronics':
                            // Process electronics repayments (money paid reduces electronics liability)
                            $isMonthlyContribution = $this->isMonthlyContribution($sessionData, 'electronics');

                            if ($isMonthlyContribution) {
                                if ($amount > 0) {
                                    // 1) Prevent over-deduction (Negative Balance)
                                    $currentElectronicsBalance = \App\Services\FinancialCalculationService::calculateElectronicsBalance($user);
                                    
                                    if ($currentElectronicsBalance <= 0) {
                                        Log::warning("Skipped electronics repayment for user {$user->id}: Balance is already 0 or negative.");
                                        continue 2;
                                    }

                                    if ($amount > $currentElectronicsBalance) {
                                        Log::info("Capping electronics repayment for user {$user->id} from {$amount} to {$currentElectronicsBalance} to prevent negative balance.");
                                        $amount = $currentElectronicsBalance;
                                    }

                                    // 2) Log an electronics repayment entry (new schema)
                                    $elxRef = 'MAB-ELX-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . Str::random(6);

                                    Electronics::create([
                                        'user_id' => $user->id,
                                        'amount' => $amount,
                                        'transaction_type' => 'repayment',
                                        'payment_method' => 'MAB',
                                        'reference_number' => $elxRef,
                                        'description' => $description . ' - Electronics Repayment',
                                        'processed_by' => Auth::id(),
                                    ]);
                                    Log::info("Logged electronics repayment for user {$user->id}: amount = {$amount}");

                                    Transaction::create([
                                        'user_id' => $user->id,
                                        'type' => 'electronics_repayment',
                                        'amount' => $amount,
                                        'description' => $description . ' - Electronics Repayment',
                                        'reference' => $elxRef,
                                        'status' => 'completed',
                                        'transaction_date' => $transactionDate,
                                    ]);
                                }
                            } else {
                                // Cumulative mode: $amount is the TARGET balance
                                $currentElectronicsBalance = \App\Services\FinancialCalculationService::calculateElectronicsBalance($user);
                                $difference = $amount - $currentElectronicsBalance;

                                if ($difference !== 0.0) {
                                    $elxRef = 'MAB-ELX-REC-' . date('Ymd') . '-' . Str::random(6);
                                    
                                    Electronics::create([
                                        'user_id' => $user->id,
                                        'amount' => abs($difference),
                                        'transaction_type' => $difference > 0 ? 'purchase' : 'repayment',
                                        'payment_method' => 'MAB',
                                        'reference_number' => $elxRef,
                                        'description' => $description . ' (Balance Reconciliation)',
                                        'processed_by' => Auth::id(),
                                    ]);

                                    Transaction::create([
                                        'user_id' => $user->id,
                                        'type' => $difference > 0 ? 'electronics_purchase' : 'electronics_repayment',
                                        'amount' => abs($difference),
                                        'description' => $description . ' (Balance Reconciliation)',
                                        'reference' => $elxRef,
                                        'status' => 'completed',
                                        'transaction_date' => $transactionDate,
                                    ]);
                                    Log::info("Reconciled electronics for user {$user->id}: from {$currentElectronicsBalance} to {$amount}");
                                }
                            }
                            break;
                    }
                    } catch (\Exception $e) {
                        Log::error("Error processing field {$field} for member " . $record['coopno'] . ": " . $e->getMessage());
                        // Continue to next field, don't fail the member
                        continue;
                    }
                }

                $successCount++;
            }

            DB::commit();

            // Update monthly upload record
            $monthlyUpload->update([
                'processed_records' => $successCount,
                'failed_records' => $errorCount,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            $errorCount++;

            // Log the error
            Log::error('Bulk update error: ' . $e->getMessage());

            // Mark monthly upload as failed
            $monthlyUpload->markAsFailed($e->getMessage());

            return redirect()->route('admin.bulk_updates')
                ->with('error', 'An error occurred during processing: ' . $e->getMessage());
        }

        // Mark monthly upload as completed
        $summary = [
            'total_records' => count($dataRows),
            'processed_records' => $successCount,
            'failed_records' => $errorCount,
            'update_fields' => $sessionData['update_fields'],
            'processing_time' => now()->diffInSeconds($monthlyUpload->upload_started_at),
        ];

        $monthlyUpload->markAsCompleted($summary);

        // Clean up the temporary file
        Storage::delete($sessionData['file_path']);
        Session::forget('bulk_update_' . $sessionId);

        return view('admin.bulk_upload_success', [
            'monthlyUpload' => $monthlyUpload->fresh(), // Get fresh data with updated counts
        ]);
    }

    /**
     * Determine if the uploaded data represents monthly contributions or cumulative balances
     */
    private function isMonthlyContribution($sessionData, $fieldType = null)
    {
        // First check if upload_type is explicitly set
        if (isset($sessionData['upload_type'])) {
            return $sessionData['upload_type'] === 'monthly_contributions';
        }

        // Fallback: Check the description for keywords that indicate monthly contributions
        $description = strtolower($sessionData['description'] ?? '');
        $monthlyKeywords = ['monthly', 'contribution', 'payment', 'deposit'];
        $cumulativeKeywords = ['balance', 'total', 'cumulative', 'reconciliation'];

        foreach ($monthlyKeywords as $keyword) {
            if (strpos($description, $keyword) !== false) {
                return true;
            }
        }

        foreach ($cumulativeKeywords as $keyword) {
            if (strpos($description, $keyword) !== false) {
                return false;
            }
        }

        // Default behavior: treat as monthly contributions for safety
        // This prevents accidental balance overwrites
        return true;
    }

    /**
     * Show upload success page
     */
    public function showSuccess(MonthlyUpload $upload)
    {
        return view('admin.bulk_upload_success', [
            'monthlyUpload' => $upload->load('uploader'),
        ]);
    }

    /**
     * Show detailed transaction history for a monthly upload
     */
    public function showTransactions(MonthlyUpload $upload)
    {
        $transactionDate = Carbon::create($upload->year, $upload->month, 1);

        // Get all transactions for this month
        $shareTransactions = ShareTransaction::whereMonth('transaction_date', $upload->month)
            ->whereYear('transaction_date', $upload->year)
            ->with('user.member')
            ->orderBy('created_at', 'desc')
            ->get();

        $savingTransactions = SavingTransaction::whereMonth('transaction_date', $upload->month)
            ->whereYear('transaction_date', $upload->year)
            ->with('user.member')
            ->orderBy('created_at', 'desc')
            ->get();

        // Separate loan principal repayments and interest payments
        $loanPrincipalPayments = LoanPayment::whereMonth('payment_date', $upload->month)
            ->whereYear('payment_date', $upload->year)
            ->where('notes', 'LIKE', '%Principal Repayment%')
            ->with('loan.user.member')
            ->orderBy('created_at', 'desc')
            ->get();

        $loanInterestPayments = LoanPayment::whereMonth('payment_date', $upload->month)
            ->whereYear('payment_date', $upload->year)
            ->where('notes', 'LIKE', '%Interest Payment%')
            ->with('loan.user.member')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get commodity transactions for this month (with distinct to avoid duplicates)
        $commodityTransactions = CommodityTransaction::whereMonth('transaction_date', $upload->month)
            ->whereYear('transaction_date', $upload->year)
            ->with('user.member')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get electronics transactions for this month
        $electronicsTransactions = Electronics::whereMonth('created_at', $upload->month)
            ->whereYear('created_at', $upload->year)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get entrance fee transactions for this month
        $entranceTransactions = Transaction::where('type', 'entrance_fee')
            ->whereMonth('created_at', $upload->month)
            ->whereYear('created_at', $upload->year)
            ->with('user.member')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get loan interest transactions (using LoanPayment model)
        // Note: Since the system uses LoanPayment, we'll show all loan payments as "interest" for now
        // This aligns with the architectural requirement for separate loan principal/interest tracking
        $loanInterestTransactions = LoanPayment::whereMonth('payment_date', $upload->month)
            ->whereYear('payment_date', $upload->year)
            ->with('loan.user.member')
            ->where('amount', '>', 0)
            ->orderBy('payment_date', 'desc')
            ->get();

        return view('admin.bulk_upload_transactions', [
            'monthlyUpload' => $upload->load('uploader'),
            'shareTransactions' => $shareTransactions,
            'savingTransactions' => $savingTransactions,
            'loanPayments' => $loanPrincipalPayments,
            'commodityTransactions' => $commodityTransactions,
            'electronicsTransactions' => $electronicsTransactions,
            'loanInterestTransactions' => $loanInterestPayments,
            'entranceTransactions' => $entranceTransactions,
        ]);
    }

    /**
     * Reverse a monthly upload and all its associated transactions
     */
    public function reverseUpload(Request $request, MonthlyUpload $upload)
    {
        $request->validate([
            'reversal_reason' => 'required|string|min:10|max:500',
        ]);

        // Security check: Only allow reversal of the absolute last upload (most recent by ID)
        $latestUpload = MonthlyUpload::orderBy('id', 'desc')
            ->first();

        if (!$latestUpload || $latestUpload->id !== $upload->id) {
            return redirect()->route('admin.bulk_updates')
                ->with('error', 'Only the most recent upload can be reversed for data integrity.');
        }

        // Check if upload is in a reversible state
        if ($upload->status !== 'completed') {
            return redirect()->route('admin.bulk_updates')
                ->with('error', 'Only completed uploads can be reversed.');
        }

        Log::info('MAB Upload reversal initiated', [
            'upload_id' => $upload->id,
            'month' => $upload->formatted_date,
            'initiated_by' => Auth::id(),
            'reason' => $request->reversal_reason,
            'processed_records' => $upload->processed_records
        ]);

        DB::beginTransaction();

        try {
            $transactionDate = Carbon::create($upload->year, $upload->month, 1);
            $reversalSummary = [
                'shares_deleted' => 0,
                'savings_deleted' => 0,
                'loan_payments_deleted' => 0,
                'commodities_deleted' => 0,
                'electronics_deleted' => 0,
                'entrance_deleted' => 0,
                'users_affected' => [],
                'balance_adjustments' => []
            ];

            // 1. Reverse Share Transactions
            $shareTransactions = ShareTransaction::whereMonth('transaction_date', $upload->month)
                ->whereYear('transaction_date', $upload->year)
                ->with('user')
                ->get();

            foreach ($shareTransactions as $transaction) {
                $user = $transaction->user;
                
                // Reverse the transaction effect on balance (if member exists)
                if ($user && $user->member) {
                    $oldBalance = $user->member->total_share_amount;
                    if ($transaction->type === 'credit') {
                        $user->member->decrement('total_share_amount', $transaction->amount);
                    } else {
                        $user->member->increment('total_share_amount', $transaction->amount);
                    }
                    
                    $reversalSummary['balance_adjustments'][] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'type' => 'shares',
                        'old_balance' => $oldBalance,
                        'new_balance' => $user->member->total_share_amount,
                        'adjustment' => $user->member->total_share_amount - $oldBalance
                    ];
                    
                    $reversalSummary['users_affected'][] = $user->id;
                }

                $transaction->delete();
                $reversalSummary['shares_deleted']++;
            }

            // 2. Reverse Saving Transactions
            $savingTransactions = SavingTransaction::whereMonth('transaction_date', $upload->month)
                ->whereYear('transaction_date', $upload->year)
                ->with('user')
                ->get();

            foreach ($savingTransactions as $transaction) {
                $user = $transaction->user;

                // Reverse the transaction effect on balance
                if ($user && $user->member) {
                    $oldBalance = $user->member->total_saving_amount;
                    if ($transaction->type === 'credit') {
                        $user->member->decrement('total_saving_amount', $transaction->amount);
                    } else {
                        $user->member->increment('total_saving_amount', $transaction->amount);
                    }

                    $reversalSummary['balance_adjustments'][] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'type' => 'savings',
                        'old_balance' => $oldBalance,
                        'new_balance' => $user->member->total_saving_amount,
                        'adjustment' => $user->member->total_saving_amount - $oldBalance
                    ];

                    $reversalSummary['users_affected'][] = $user->id;
                }

                $transaction->delete();
                $reversalSummary['savings_deleted']++;
            }

            // 3. Reverse Loan Payments
            $loanPayments = LoanPayment::whereMonth('payment_date', $upload->month)
                ->whereYear('payment_date', $upload->year)
                ->with('loan.user')
                ->get();

            foreach ($loanPayments as $payment) {
                $loan = $payment->loan;
                $user = $loan->user;
                $oldStatus = $loan->status;

                // Reverse the payment effect (Deleting payment is enough for FinancialCalculationService)
                // If the loan was marked as completed by this payment, set it back to active
                if ($loan->status === 'completed' && $payment->payment_date->isSameMonth($upload->year . '-' . $upload->month . '-01')) {
                    $loan->update(['status' => 'active', 'completed_at' => null]);
                }

                $reversalSummary['balance_adjustments'][] = [
                    'user_id' => $user->id ?? 0,
                    'user_name' => $user->name ?? 'Unknown',
                    'type' => 'loan_repayment',
                    'amount' => $payment->amount,
                    'loan_id' => $loan->id,
                    'loan_status_reverted' => $oldStatus !== $loan->status
                ];

                if ($user) $reversalSummary['users_affected'][] = $user->id;
                $payment->delete();
                $reversalSummary['loan_payments_deleted']++;
            }

            // 4. Reverse Commodity Transactions (CORRECTED for new debit logic)
            $commodityTransactions = CommodityTransaction::whereMonth('transaction_date', $upload->month)
                ->whereYear('transaction_date', $upload->year)
                ->with('user')
                ->get();

            foreach ($commodityTransactions as $transaction) {
                // Find and reverse UserCommodity balance changes
                $userCommodity = UserCommodity::where('user_id', $transaction->user_id)
                    ->where('commodity_name', $transaction->commodity_type)
                    ->first();

                if ($userCommodity) {
                    $oldBalance = $userCommodity->balance;

                    // CORRECTED: Reverse the debit operation (add back the amount)
                    if ($transaction->type === 'debit') {
                        $userCommodity->balance += $transaction->amount;
                    } else {
                        // Handle legacy credit transactions
                        $userCommodity->balance -= $transaction->amount;
                    }

                    $userCommodity->save();

                    $reversalSummary['balance_adjustments'][] = [
                        'user_id' => $transaction->user_id,
                        'user_name' => $transaction->user->name,
                        'type' => $transaction->commodity_type . '_commodities',
                        'old_balance' => $oldBalance,
                        'new_balance' => $userCommodity->balance,
                        'adjustment' => $userCommodity->balance - $oldBalance
                    ];
                }

                $reversalSummary['users_affected'][] = $transaction->user_id;
                
                // Also delete the general transaction record associated with this commodity repayment
                Transaction::where('user_id', $transaction->user_id)
                    ->where('type', 'commodity_repayment')
                    ->where('amount', $transaction->amount)
                    ->where('description', 'like', '%' . ($upload->description ?: $upload->formatted_date) . '%')
                    ->delete();

                $transaction->delete();
                $reversalSummary['commodities_deleted']++;
            }

            // 5. Reverse Electronics Transactions (NEW)
            $matchDescription = ($upload->description ?: $upload->formatted_date) . " - Electronics Repayment";
            $electronicsTransactions = Electronics::where('description', 'like', '%' . $matchDescription . '%')->get();

            foreach ($electronicsTransactions as $electronics) {
                $user = $electronics->user;
                
                // Reverse the repayment effect on balance (if member exists)
                if ($user && $user->member) {
                    $oldBalance = $user->member->total_electronics_amount;
                    // Repayment decreased the balance, so reversal should INCREASE it
                    $user->member->increment('total_electronics_amount', $electronics->amount);
                    
                    $reversalSummary['balance_adjustments'][] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'type' => 'electronics',
                        'old_balance' => $oldBalance,
                        'new_balance' => $user->member->total_electronics_amount,
                        'adjustment' => $electronics->amount
                    ];
                    
                    $reversalSummary['users_affected'][] = $user->id;
                }

                // Also delete the general transaction record associated with this electronics repayment
                Transaction::where('user_id', $electronics->user_id)
                    ->where('type', 'electronics_repayment')
                    ->where('amount', $electronics->amount)
                    ->where('description', 'like', '%' . ($upload->description ?: $upload->formatted_date) . '%')
                    ->delete();
                
                $electronics->delete();
                $reversalSummary['electronics_deleted']++;
            }

            // 6. Reverse Entrance Fee Transactions (NEW)
            $entranceTransactions = Transaction::where('type', 'entrance_fee')
                ->where('description', 'like', '%' . ($upload->description ?: $upload->formatted_date) . '%')
                ->with('user.member')
                ->get();

            foreach ($entranceTransactions as $transaction) {
                // Reset entrance fee paid status
                if ($transaction->user && $transaction->user->member) {
                    $transaction->user->member->update(['entrance_fee_paid' => false]);

                    $reversalSummary['balance_adjustments'][] = [
                        'user_id' => $transaction->user_id,
                        'user_name' => $transaction->user->name,
                        'type' => 'entrance_fee',
                        'old_balance' => 'paid',
                        'new_balance' => 'unpaid',
                        'adjustment' => 'Reset entrance fee status'
                    ];
                }

                $reversalSummary['users_affected'][] = $transaction->user_id;
                $transaction->delete();
                $reversalSummary['entrance_deleted']++;
            }

            // 7. Update the MonthlyUpload record to mark as reversed
            $upload->update([
                'status' => 'reversed',
                'error_message' => 'Upload reversed by ' . Auth::user()->name . '. Reason: ' . $request->reversal_reason,
                'processing_summary' => array_merge($upload->processing_summary ?? [], [
                    'reversal_date' => now()->toISOString(),
                    'reversed_by' => Auth::id(),
                    'reversal_reason' => $request->reversal_reason,
                    'reversal_summary' => $reversalSummary
                ])
            ]);

            // Remove duplicates from users_affected
            $reversalSummary['users_affected'] = array_unique($reversalSummary['users_affected']);

            DB::commit();

            Log::info('MAB Upload reversal completed successfully', [
                'upload_id' => $upload->id,
                'month' => $upload->formatted_date,
                'reversed_by' => Auth::id(),
                'summary' => $reversalSummary
            ]);

            return redirect()->route('admin.bulk_updates')
                ->with('success', "MAB upload for {$upload->formatted_date} has been successfully reversed. " .
                    "Deleted: {$reversalSummary['shares_deleted']} share transactions, " .
                    "{$reversalSummary['savings_deleted']} saving transactions, " .
                    "{$reversalSummary['loan_payments_deleted']} loan payments, " .
                    "{$reversalSummary['commodities_deleted']} commodity transactions. " .
                    count($reversalSummary['users_affected']) . " users affected.");

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('MAB Upload reversal failed', [
                'upload_id' => $upload->id,
                'month' => $upload->formatted_date,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.bulk_updates')
                ->with('error', 'Failed to reverse upload: ' . $e->getMessage());
        }
    }

    /**
     * Verify data integrity after upload
     */
    public function verifyIntegrity(MonthlyUpload $upload)
    {
        $results = [
            'upload' => $upload,
            'integrity_checks' => [],
            'issues_found' => [],
            'recommendations' => []
        ];

        // Check 1: Verify transaction counts match upload records
        $transactionDate = Carbon::create($upload->year, $upload->month, 1);

        $shareTransactionCount = ShareTransaction::whereMonth('transaction_date', $upload->month)
            ->whereYear('transaction_date', $upload->year)
            ->count();

        $savingTransactionCount = SavingTransaction::whereMonth('transaction_date', $upload->month)
            ->whereYear('transaction_date', $upload->year)
            ->count();

        $results['integrity_checks']['transaction_counts'] = [
            'share_transactions' => $shareTransactionCount,
            'saving_transactions' => $savingTransactionCount,
            'expected_records' => $upload->processed_records,
        ];

        // Check 2: Verify balance consistency
        $membersWithInconsistentBalances = [];
        $members = User::whereHas('member')->with('member')->get();

        foreach ($members as $member) {
            $shareTransactionSum = ShareTransaction::where('user_id', $member->id)->sum('amount');
            $savingTransactionSum = SavingTransaction::where('user_id', $member->id)->sum('amount');

            if (abs($shareTransactionSum - $member->member->total_share_amount) > 0.01) {
                $membersWithInconsistentBalances[] = [
                    'member' => $member->name,
                    'member_number' => $member->member->member_number,
                    'type' => 'shares',
                    'calculated_balance' => $shareTransactionSum,
                    'stored_balance' => $member->member->total_share_amount,
                    'difference' => $shareTransactionSum - $member->member->total_share_amount
                ];
            }

            if (abs($savingTransactionSum - $member->member->total_saving_amount) > 0.01) {
                $membersWithInconsistentBalances[] = [
                    'member' => $member->name,
                    'member_number' => $member->member->member_number,
                    'type' => 'savings',
                    'calculated_balance' => $savingTransactionSum,
                    'stored_balance' => $member->member->total_saving_amount,
                    'difference' => $savingTransactionSum - $member->member->total_saving_amount
                ];
            }
        }

        $results['integrity_checks']['balance_consistency'] = [
            'total_members_checked' => $members->count(),
            'inconsistent_balances' => count($membersWithInconsistentBalances),
            'details' => $membersWithInconsistentBalances
        ];

        // Add issues and recommendations
        if (count($membersWithInconsistentBalances) > 0) {
            $results['issues_found'][] = "Found " . count($membersWithInconsistentBalances) . " members with inconsistent balances";
            $results['recommendations'][] = "Review and reconcile member balances";
        }

        if ($upload->failed_records > 0) {
            $results['issues_found'][] = "{$upload->failed_records} records failed to process";
            $results['recommendations'][] = "Review failed records and re-upload if necessary";
        }

        return view('admin.bulk_upload_integrity', $results);
    }

    /**
     * Download a CSV template
     */
    public function downloadTemplate()
    {
        // Set headers
        $headers = [
            'S/NO', 'COOPNO', 'SURNAME', 'OTHERNAMES', 'ENTRANCE', 'SHARES',
            'SAVINGS', 'LOAN REPAY', 'LOAN INT', 'ESSENTIAL', 'NON-ESSENTIAL',
            'ELECTRONICS', 'TOTAL DEC'
        ];

        // Add sample data matching the user's monthly contribution data - 10 records
        $sampleData = [
            [1, 'P/SS/001', 'DOE', 'JOHN', '', 25000, '', '', 5000, 15000, 8000, 5000, 68000],
            [2, 'P/SS/002', 'SMITH', 'JANE', 'Yes', '', 15000, '', 7500, 8000, 5000, 3000, 48500],
            [3, 'P/SS/003', 'JOHNSON', 'MIKE', '', 30000, '', '', 10000, 20000, 12000, 7500, 94500],
            [4, 'P/SS/004', 'WILSON', 'SARAH', '', 5000, 20000, '', 6000, 12000, 6000, 4000, 56000],
            [5, 'P/SS/005', 'BROWN', 'DAVID', 'Yes', 5000, 28000, '', 8000, 18000, 9000, 6000, 81000],
            [6, 'P/SS/006', 'DAVIS', 'LISA', '', 18000, '', '', 4500, 10000, 5000, 3500, 47000],
            [7, 'P/SS/007', 'MILLER', 'ROBERT', '', 22000, '', '', 5500, 14000, 7000, 4500, 61000],
            [8, 'P/SS/008', 'GARCIA', 'EMILY', 'Yes', '', 12000, '', 3000, 8000, 4000, 2500, 35500],
            [9, 'P/SS/009', 'RODRIGUEZ', 'JAMES', '', 26000, '', '', 7000, 16000, 8000, 5500, 73500],
            [10, 'P/SS/010', 'LOPEZ', 'MARIA', '', 24000, '', '', 6500, 17000, 8500, 5000, 70000],
        ];

        // Create CSV content
        $output = fopen('php://temp', 'r+');

        // Add headers
        fputcsv($output, $headers);

        // Add sample data
        foreach ($sampleData as $row) {
            fputcsv($output, $row);
        }

        // Get CSV content
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        // Set response headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="member_contributions_template.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        // Return CSV file
        return response($csv, 200, $headers);
    }

    /**
     * View detailed report of a specific upload
     *
     * @param string $id The upload ID to view details for
     * @return \Illuminate\Http\RedirectResponse
     */
    public function viewDetails($id)
    {
        // In a real application, you would fetch the upload details from the database using $id
        // For this example, we'll return to the bulk updates page with an info message
        return redirect()->route('admin.bulk_updates')
            ->with('info', "Detailed reports for upload #$id will be implemented in a future update.");
    }

    /**
     * Convert various boolean representations to boolean
     */
    private function convertToBoolean($value)
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return $value > 0;
        }

        if (is_string($value)) {
            $lowerValue = strtolower(trim($value));
            return in_array($lowerValue, ['yes', 'y', 'true', '1', 'on']);
        }

        return false;
    }

    /**
     * Map column headers to their indices
     */
    private function mapColumns($headers)
    {
        $columnMap = [];

        // Convert headers to lowercase for case-insensitive matching and trim whitespace
        $lowercaseHeaders = array_map(function($h) {
            return strtolower(trim($h));
        }, $headers);

        // Map common column names to their indices
        $columnMappings = [
            'sno' => ['s/no', 'sno', 'serial', 'id', 's.no'],
            'coopno' => ['coopno', 'coop no', 'cooperative number', 'member id', 'coop_no', 'member_no'],
            'surname' => ['surname', 'last name', 'family name'],
            'othernames' => ['othernames', 'other names', 'first name', 'given name'],
            'entrance' => ['entrance', 'entrance fee', 'entrance_fee', 'entrance fee paid'],
            'shares' => ['shares', 'share', 'shares balance', 'share balance', 'total shares'],
            'savings' => ['savings', 'saving', 'savings balance', 'saving balance', 'total savings'],
            'loan_repay' => ['loan repay', 'loan repayment', 'repayment', 'loan balance', 'loan principal'],
            'loan_int' => ['loan int', 'loan interest', 'interest', 'interest balance', 'loan interest balance'],
            'essential' => ['essential', 'essentials', 'essential balance'],
            'non_essential' => ['non-essential', 'non essential', 'nonessential', 'non-essen', 'non-essential balance'],
            'electronics' => ['electronics', 'electronic', 'electroni', 'electronics balance'],
            'total' => ['total', 'total dec', 'total deduction', 'sum', 'total balance']
        ];

        foreach ($columnMappings as $key => $possibleNames) {
            foreach ($possibleNames as $name) {
                $index = array_search($name, $lowercaseHeaders);
                if ($index !== false) {
                    $columnMap[$key] = $index;
                    break;
                }
            }

            // If not found, set a default
            if (!isset($columnMap[$key])) {
                $columnMap[$key] = null;
                Log::warning("Column mapping failed for field: $key. No matching header found.");
            }
        }

        Log::info('Final column mapping', ['map' => $columnMap]);

        return $columnMap;
    }

    /**
     * Format amount for display
     */
    private function formatAmount($value)
    {
        if ($value === null || $value === '') {
            return '-';
        }

        // Remove any non-numeric characters except decimal point
        $value = preg_replace('/[^0-9.]/', '', $value);

        // Format with commas
        return number_format((float)$value, 0, '.', ',');
    }

    /**
     * Process cascading loan repayment for MAB uploads (oldest loans first)
     */
    private function processCascadingLoanRepaymentMAB(User $user, $amount, $description, $transactionDate)
    {
        $remainingAmount = $amount;
        $activeLoans = $user->loans()->whereIn('status', ['active', 'approved'])->orderBy('created_at', 'asc')->get();

        foreach ($activeLoans as $loan) {
            if ($remainingAmount <= 0) break;

            // Calculate remaining principal for this loan
            $principalPayments = $loan->payments()
                ->where('status', 'paid')
                ->where('notes', 'LIKE', '%Principal Repayment%')
                ->sum('amount');

            $remainingPrincipal = $loan->amount - $principalPayments;

            if ($remainingPrincipal > 0) {
                $paymentAmount = min($remainingAmount, $remainingPrincipal);

                // Create loan payment record
                LoanPayment::create([
                    'loan_id' => $loan->id,
                    'amount' => $paymentAmount,
                    'payment_date' => $transactionDate,
                    'due_date' => $transactionDate,
                    'status' => 'paid',
                    'payment_method' => 'deduction',
                    'notes' => $description . ' - Principal Repayment'
                ]);

                $remainingAmount -= $paymentAmount;

                // Check if loan principal is fully paid
                if (($remainingPrincipal - $paymentAmount) <= 0) {
                    // Check if interest is also fully paid
                    $storedRate = $loan->interest_rate ?? 0.10;
                    $interestRate = $storedRate > 1 ? $storedRate / 100 : $storedRate;
                    $interestDue = $loan->amount * $interestRate;
                    $interestPayments = $loan->payments()
                        ->where('status', 'paid')
                        ->where('notes', 'LIKE', '%Interest Payment%')
                        ->sum('amount');

                    if ($interestPayments >= $interestDue) {
                        $loan->update([
                            'status' => 'completed',
                            'completed_at' => now()
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Parse numeric amount from mixed input (Excel cell, formatted string)
     */
    private function parseNumericAmount($value)
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float)$value;
        }

        // Remove commas, currency symbols, and spaces
        $value = str_replace([',', '₦', '$', '€', '£', ' '], '', (string)$value);
        
        // Extract numeric part including decimal point
        if (preg_match('/([0-9.]+)/', $value, $matches)) {
            return (float)$matches[1];
        }

        return 0.0;
    }

    /**
     * Reconcile loan principal balance for MAB uploads
     */
    private function reconcileLoanPrincipalBalanceMAB(User $user, $targetBalance, $description, $transactionDate)
    {
        // Use FinancialCalculationService for consistent balance calculation
        $currentPrincipal = FinancialCalculationService::calculateLoanBalance($user);
        
        // Check if user has any active or approved loans
        $activeLoans = $user->loans()->whereIn('status', ['active', 'approved'])->orderBy('created_at', 'asc')->get();
        if ($activeLoans->isEmpty()) {
            if ($targetBalance > 0) {
                Log::warning("Attempted to set loan principal balance to {$targetBalance} for user {$user->id} but no active/approved loans found.");
            }
            return;
        }

        $difference = $currentPrincipal - $targetBalance;

        if (abs($difference) < 0.01) {
            // Already at target (within 1 cent tolerance)
            Log::info("Loan principal for user {$user->id} already at target balance {$targetBalance}");
            return;
        }

        if ($difference > 0) {
            // Current > Target: Need to apply more repayments
            $this->processCascadingLoanRepaymentMAB($user, $difference, $description, $transactionDate);
            Log::info("Reconciled loan principal for user {$user->id}: applied {$difference} repayment to reach target balance {$targetBalance}");
        } else {
            // Current < Target: Need to reverse some payments (spreadsheet shows higher balance)
            // This happens when the spreadsheet is the source of truth and shows a higher outstanding balance
            $amountToReverse = abs($difference);
            
            // Create negative payment records to increase the outstanding balance
            // We'll distribute this across loans proportionally
            $totalCurrentPrincipal = max($currentPrincipal, 0.01); // Avoid division by zero
            
            foreach ($activeLoans as $loan) {
                if ($amountToReverse <= 0) break;
                
                // Calculate this loan's proportion of the adjustment
                $principalPayments = $loan->payments()
                    ->where('status', 'paid')
                    ->where('notes', 'LIKE', '%Principal Repayment%')
                    ->sum('amount');
                
                $loanRemainingPrincipal = max(0, $loan->amount - $principalPayments);
                
                // If this loan has remaining principal, it gets a share of the adjustment
                if ($loanRemainingPrincipal > 0 || $totalCurrentPrincipal == 0) {
                    $proportion = $totalCurrentPrincipal > 0 ? ($loanRemainingPrincipal / $totalCurrentPrincipal) : (1 / $activeLoans->count());
                    $adjustmentAmount = min($amountToReverse, $amountToReverse * $proportion);
                    
                    // Create a negative payment (reversal) to increase outstanding balance
                    LoanPayment::create([
                        'loan_id' => $loan->id,
                        'amount' => -$adjustmentAmount,
                        'payment_date' => $transactionDate,
                        'due_date' => $transactionDate,
                        'status' => 'paid',
                        'payment_method' => 'adjustment',
                        'notes' => $description . ' - Principal Balance Adjustment (Reconciliation)'
                    ]);
                    
                    $amountToReverse -= $adjustmentAmount;
                }
            }
            
            // If there's still remaining amount, apply it to the first loan
            if ($amountToReverse > 0.01 && !$activeLoans->isEmpty()) {
                $firstLoan = $activeLoans->first();
                LoanPayment::create([
                    'loan_id' => $firstLoan->id,
                    'amount' => -$amountToReverse,
                    'payment_date' => $transactionDate,
                    'due_date' => $transactionDate,
                    'status' => 'paid',
                    'payment_method' => 'adjustment',
                    'notes' => $description . ' - Principal Balance Adjustment (Reconciliation Remainder)'
                ]);
            }
            
            Log::info("Reconciled loan principal for user {$user->id}: adjusted balance UP by {$difference} to reach target {$targetBalance} (spreadsheet override)");
        }
    }

    /**
     * Reconcile loan interest balance for MAB uploads
     */
    private function reconcileLoanInterestBalanceMAB(User $user, $targetBalance, $description, $transactionDate)
    {
        // Use FinancialCalculationService for consistent balance calculation across ALL loans
        $currentInterestBalance = FinancialCalculationService::calculateLoanInterest($user);
        
        // Check if user has any active or approved loans
        $activeLoans = $user->loans()->whereIn('status', ['active', 'approved'])->orderBy('created_at', 'asc')->get();
        if ($activeLoans->isEmpty()) {
            if ($targetBalance > 0) {
                Log::warning("Attempted to set loan interest balance to {$targetBalance} for user {$user->id} but no active/approved loans found.");
            }
            return;
        }

        $difference = $currentInterestBalance - $targetBalance;

        if (abs($difference) < 0.01) {
            // Already at target (within 1 cent tolerance)
            Log::info("Loan interest for user {$user->id} already at target balance {$targetBalance}");
            return;
        }

        if ($difference > 0) {
            // Current > Target: Apply interest payments across loans (oldest first)
            $remainingPayment = $difference;
            
            foreach ($activeLoans as $loan) {
                if ($remainingPayment <= 0) break;
                
                // Calculate interest owed for this specific loan
                $storedRate = $loan->interest_rate ?? 0.10;
                $interestRate = $storedRate > 1 ? $storedRate / 100 : $storedRate;
                $totalInterestDue = $loan->amount * $interestRate;
                $interestPaid = $loan->payments()
                    ->where('status', 'paid')
                    ->where('notes', 'LIKE', '%Interest Payment%')
                    ->sum('amount');
                
                $interestOwed = max(0, $totalInterestDue - $interestPaid);
                
                if ($interestOwed > 0) {
                    $paymentAmount = min($remainingPayment, $interestOwed);
                    
                    LoanPayment::create([
                        'loan_id' => $loan->id,
                        'amount' => $paymentAmount,
                        'payment_date' => $transactionDate,
                        'due_date' => $transactionDate,
                        'status' => 'paid',
                        'payment_method' => 'deduction',
                        'notes' => $description . ' - Interest Payment'
                    ]);
                    
                    $remainingPayment -= $paymentAmount;
                }
            }
            
            Log::info("Reconciled loan interest for user {$user->id}: applied {$difference} payment to reach target balance {$targetBalance}");
        } else {
            // Current < Target: Need to reverse some interest payments (spreadsheet shows higher balance)
            $amountToReverse = abs($difference);
            
            // Create negative payment records to increase the outstanding interest
            // Distribute across loans proportionally based on their interest owed
            $totalCurrentInterest = max($currentInterestBalance, 0.01); // Avoid division by zero
            
            foreach ($activeLoans as $loan) {
                if ($amountToReverse <= 0) break;
                
                // Calculate interest for this loan
                $storedRate = $loan->interest_rate ?? 0.10;
                $interestRate = $storedRate > 1 ? $storedRate / 100 : $storedRate;
                $totalInterestDue = $loan->amount * $interestRate;
                $interestPaid = $loan->payments()
                    ->where('status', 'paid')
                    ->where('notes', 'LIKE', '%Interest Payment%')
                    ->sum('amount');
                
                $interestOwed = max(0, $totalInterestDue - $interestPaid);
                
                // If this loan has interest owed, it gets a share of the adjustment
                if ($interestOwed > 0 || $totalCurrentInterest == 0) {
                    $proportion = $totalCurrentInterest > 0 ? ($interestOwed / $totalCurrentInterest) : (1 / $activeLoans->count());
                    $adjustmentAmount = min($amountToReverse, $amountToReverse * $proportion);
                    
                    // Create a negative payment (reversal) to increase outstanding interest
                    LoanPayment::create([
                        'loan_id' => $loan->id,
                        'amount' => -$adjustmentAmount,
                        'payment_date' => $transactionDate,
                        'due_date' => $transactionDate,
                        'status' => 'paid',
                        'payment_method' => 'adjustment',
                        'notes' => $description . ' - Interest Balance Adjustment (Reconciliation)'
                    ]);
                    
                    $amountToReverse -= $adjustmentAmount;
                }
            }
            
            // If there's still remaining amount, apply it to the first loan
            if ($amountToReverse > 0.01 && !$activeLoans->isEmpty()) {
                $firstLoan = $activeLoans->first();
                LoanPayment::create([
                    'loan_id' => $firstLoan->id,
                    'amount' => -$amountToReverse,
                    'payment_date' => $transactionDate,
                    'due_date' => $transactionDate,
                    'status' => 'paid',
                    'payment_method' => 'adjustment',
                    'notes' => $description . ' - Interest Balance Adjustment (Reconciliation Remainder)'
                ]);
            }
            
            Log::info("Reconciled loan interest for user {$user->id}: adjusted balance UP by {$difference} to reach target {$targetBalance} (spreadsheet override)");
        }
    }
}

