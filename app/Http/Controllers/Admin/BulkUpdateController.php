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

        // Compute next allowed month (sequential rule based on last successful upload)
        $latestCompleted = MonthlyUpload::where('status', 'completed')
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

        // Sequential rule: you can only upload the month immediately after the last successful upload
        $latestCompleted = MonthlyUpload::where('status', 'completed')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();

        if ($latestCompleted) {
            $next = Carbon::create($latestCompleted->year, $latestCompleted->month, 1)->addMonth();
            if (!($year === (int) $next->year && $month === (int) $next->month)) {
                return redirect()->route('admin.bulk_updates')
                    ->with('error', 'Invalid month selected. Next allowed month is ' . $next->format('F Y') . '.');
            }
        }

        // Check if an upload record already exists for this month
        $existingUpload = MonthlyUpload::where('year', $year)
            ->where('month', $month)
            ->first();

        // If a fully completed upload exists for this month, block duplicates
        if ($existingUpload && $existingUpload->status === 'completed') {
            return redirect()->route('admin.bulk_updates')
                ->with('error', "Financial records for {$transactionDate->format('F Y')} have already been uploaded.");
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

        // Get headers (first row)
        $headers = array_shift($rows);

        Log::info('File processed', [
            'total_rows' => count($rows),
            'headers' => $headers
        ]);

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
            $financialFields = ['entrance', 'shares', 'savings', 'loan_repay', 'loan_int', 'essential', 'non_essential', 'electronics'];

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
                    'name' => trim(($row[$columnMap['surname'] ?? 2] ?? '') . ' ' . ($row[$columnMap['othernames'] ?? 3] ?? '')),
                    'entrance' => floatval($row[$columnMap['entrance'] ?? 4] ?? 0),
                    'shares' => floatval($row[$columnMap['shares'] ?? 5] ?? 0),
                    'savings' => floatval($row[$columnMap['savings'] ?? 6] ?? 0),
                    'loan_repay' => floatval($row[$columnMap['loan_repay'] ?? 7] ?? 0),
                    'loan_int' => floatval($row[$columnMap['loan_int'] ?? 8] ?? 0),
                    'essential' => floatval($row[$columnMap['essential'] ?? 9] ?? 0),
                    'non_essential' => floatval($row[$columnMap['non_essential'] ?? 10] ?? 0),
                    'electronics' => floatval($row[$columnMap['electronics'] ?? 11] ?? 0),
                    'total' => floatval($row[$columnMap['total'] ?? 12] ?? 0)
                ];
            }

            // Get indices (with defaults if mapping fails)
            $snoIndex = $columnMap['sno'] ?? 0;
            $surnameIndex = $columnMap['surname'] ?? 2;
            $othernamesIndex = $columnMap['othernames'] ?? 3;
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
                'upload_type' => 'financial_records',
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
                'upload_type' => 'financial_records',
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
        // We need to re-read the file since we only stored a preview
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $rows = [];

        if ($extension === 'csv') {
            $fileContent = file_get_contents($filePath);
            $lines = explode("\n", $fileContent);
            foreach ($lines as $line) {
                if (trim($line)) {
                    $rows[] = str_getcsv($line);
                }
            }
        } else {
            // Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = [];
                for ($col = 'A'; $col <= 'M'; $col++) {
                    $cellValue = $worksheet->getCell($col . $row)->getCalculatedValue();
                    $rowData[] = $cellValue;
                }
                $rows[] = $rowData;
            }
        }

        // Skip header rows and process data
        $dataRows = array_slice($rows, 2);
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
                $validRecords[] = [
                    'coopno' => $coopno,
                    'surname' => trim($row[$columnMap['surname'] ?? 2] ?? ''),
                    'othernames' => trim($row[$columnMap['othernames'] ?? 3] ?? ''),
                    'entrance' => $row[$columnMap['entrance'] ?? 4] ?? 0,
                    'shares' => $row[$columnMap['shares'] ?? 5] ?? 0,
                    'savings' => $row[$columnMap['savings'] ?? 6] ?? 0,
                    'loan_repay' => $row[$columnMap['loan_repay'] ?? 7] ?? 0,
                    'loan_int' => $row[$columnMap['loan_int'] ?? 8] ?? 0,
                    'essential' => $row[$columnMap['essential'] ?? 9] ?? 0,
                    'non_essential' => $row[$columnMap['non_essential'] ?? 10] ?? 0,
                    'electronics' => $row[$columnMap['electronics'] ?? 11] ?? 0,
                ];
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

                // Process each update field
                foreach ($updateFields as $field) {
                    $amount = $this->formatAmount($record[$field]);
                    if ($amount === '-') {
                        $amount = 0;
                    } else {
                        $amount = (float)str_replace(',', '', $amount);
                    }

                    // Skip if amount is zero and missing_data is set to 'skip'
                    if ($amount == 0 && $missingData == 'skip') {
                        continue;
                    }

                    switch ($field) {
                        case 'entrance':
                            // Process entrance fee payment
                            if ($amount > 0) {
                                // Check if entrance fee has already been paid
                                if ($user->member->entrance_fee_paid) {
                                    Log::info("Entrance fee already paid for user {$user->id}, skipping");
                                    continue 2;
                                }

                                // Create entrance fee transaction
                                Transaction::create([
                                    'user_id' => $user->id,
                                    'type' => 'entrance_fee',
                                    'amount' => $amount,
                                    'description' => $description . ' - Entrance Fee Payment',
                                    'reference' => 'ENT-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                                    'status' => 'completed',
                                    'transaction_date' => $transactionDate,
                                ]);

                                // Mark entrance fee as paid
                                $user->member->update(['entrance_fee_paid' => true]);

                                Log::info("Processed entrance fee for user {$user->id}: {$amount}");
                            }
                            break;

                        case 'shares':
                            // Update shares
                            if ($amount > 0) {
                                // Check if exceeds maximum share contribution
                                $maxShareContribution = config('business_rules.shares.maximum_contribution', 10000);
                                $currentShares = $user->member->total_share_amount ?? 0;

                                // Determine if this is a monthly contribution or cumulative balance
                                $isMonthlyContribution = $this->isMonthlyContribution($sessionData, 'shares');

                                if ($isMonthlyContribution) {
                                    // This is a monthly contribution - add to existing balance
                                    if (($currentShares + $amount) > $maxShareContribution) {
                                        Log::warning("Share contribution for user {$user->id} would exceed maximum limit of {$maxShareContribution}");
                                        continue 2;
                                    }

                                    ShareTransaction::create([
                                        'user_id' => $user->id,
                                        'amount' => $amount,
                                        'type' => 'credit',
                                        'description' => $description,
                                        'transaction_date' => $transactionDate,
                                    ]);

                                    $user->member->increment('total_share_amount', $amount);
                                } else {
                                    // This is a cumulative balance - set the total balance
                                    if ($amount > $maxShareContribution) {
                                        Log::warning("Share balance for user {$user->id} exceeds maximum limit of {$maxShareContribution}");
                                        continue 2;
                                    }

                                    // Calculate the difference for the transaction
                                    $difference = $amount - $currentShares;

                                    if ($difference > 0) {
                                        // CRITICAL: Only allow share increases, never decreases
                                        ShareTransaction::create([
                                            'user_id' => $user->id,
                                            'amount' => $difference,
                                            'type' => 'credit',
                                            'description' => $description . ' (Balance Reconciliation)',
                                            'transaction_date' => $transactionDate,
                                        ]);

                                        $user->member->update(['total_share_amount' => $amount]);
                                    } elseif ($difference < 0) {
                                        // Log warning but don't process share reductions
                                        Log::warning("Attempted to reduce shares for user {$user->id} from {$currentShares} to {$amount}. Share reductions are not allowed in MAB uploads.");
                                    }
                                }
                            }
                            break;

                        case 'savings':
                            // Update savings - ADDITION ONLY for MAB uploads
                            if ($amount > 0) {
                                $currentSavings = $user->member->total_saving_amount ?? 0;
                                $isMonthlyContribution = $this->isMonthlyContribution($sessionData, 'savings');

                                if ($isMonthlyContribution) {
                                    // This is a monthly contribution - add to existing balance
                                    SavingTransaction::create([
                                        'user_id' => $user->id,
                                        'amount' => $amount,
                                        'type' => 'credit',
                                        'description' => $description,
                                        'transaction_date' => $transactionDate,
                                    ]);

                                    $user->member->increment('total_saving_amount', $amount);
                                } else {
                                    // This is a cumulative balance - but only allow increases in MAB uploads
                                    $difference = $amount - $currentSavings;

                                    if ($difference > 0) {
                                        // Only allow savings increases in MAB uploads
                                        SavingTransaction::create([
                                            'user_id' => $user->id,
                                            'amount' => $difference,
                                            'type' => 'credit',
                                            'description' => $description . ' (Balance Reconciliation)',
                                            'transaction_date' => $transactionDate,
                                        ]);

                                        $user->member->update(['total_saving_amount' => $amount]);
                                    } elseif ($difference < 0) {
                                        // Log warning but don't process savings reductions in MAB uploads
                                        Log::warning("Attempted to reduce savings for user {$user->id} from {$currentSavings} to {$amount}. Savings reductions are not allowed in MAB uploads.");
                                    }
                                }
                            }
                            break;

                        case 'loan_repay':
                            // Process loan principal repayment with cascading logic (oldest loans first)
                            if ($amount > 0) {
                                $this->processCascadingLoanRepaymentMAB($user, $amount, $description, $transactionDate);
                                Log::info("Processed loan principal repayment for user {$user->id}: {$amount}");
                            }
                            break;

                        case 'loan_int':
                            // Process loan interest payment - SUBTRACTION operation (separate from principal)
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

                                    // Note: Interest payments don't reduce principal balance
                                    // They are tracked separately for reporting purposes
                                    Log::info("Processed loan interest payment for user {$user->id}: {$amount}");
                                } else {
                                    // No active loan found - log warning and skip
                                    Log::warning("Loan interest payment attempted for user {$user->id} but no active loan found. Amount: {$amount}");
                                }
                            }
                            break;

                        case 'essential':
                        case 'non_essential':
                            // CORRECTED: Process commodity repayments (SUBTRACTION operation)
                            // Rationale: Members collect goods on credit and repay with money through MAB deductions
                            if ($amount > 0) {
                                // Create commodity transaction record as DEBIT (repayment)
                                CommodityTransaction::create([
                                    'user_id' => $user->id,
                                    'commodity_type' => $field, // 'essential' or 'non_essential'
                                    'amount' => $amount,
                                    'type' => 'debit', // CORRECTED: Changed from 'credit' to 'debit'
                                    'description' => $description . ' - ' . ucfirst(str_replace('_', ' ', $field)) . ' Repayment',
                                    'transaction_date' => $transactionDate,
                                    'processed_by' => Auth::id(),
                                ]);

                                // Also add a general transaction entry so it shows in user recent transactions
                                Transaction::create([
                                    'user_id' => $user->id,
                                    'type' => 'commodity_repayment',
                                    'amount' => $amount,
                                    'description' => $description . ' - ' . ucfirst(str_replace('_', ' ', $field)) . ' Repayment',
                                    'reference' => 'MAB-COM-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . Str::random(6),
                                    'status' => 'completed',
                                    'transaction_date' => $transactionDate,
                                ]);

                                // Find or create user commodity balance for this specific type
                                $userCommodity = UserCommodity::where('user_id', $user->id)
                                    ->where('commodity_name', $field)
                                    ->first();

                                if ($userCommodity) {
                                    // CORRECTED: Subtract amount (repayment reduces outstanding balance)
                                    $userCommodity->balance = max(0, ($userCommodity->balance ?? 0) - $amount);
                                    $userCommodity->save();
                                } else {
                                    // If no existing balance, create with negative balance (overpayment scenario)
                                    UserCommodity::create([
                                        'user_id' => $user->id,
                                        'commodity_name' => $field, // 'essential' or 'non_essential'
                                        'balance' => -$amount // Negative indicates overpayment/credit
                                    ]);
                                }

                                Log::info("Processed commodity repayment for user {$user->id}: {$field} = {$amount}");
                            }
                            break;

                        case 'electronics':
                            // Process electronics repayments (money paid reduces electronics liability)
                            if ($amount > 0) {
                                // 1) Log an electronics repayment entry (new schema)
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

                                // 2) Record a commodity transaction for electronics (debit = repayment)
                                CommodityTransaction::create([
                                    'user_id' => $user->id,
                                    'commodity_type' => 'electronics',
                                    'amount' => $amount,
                                    'type' => 'debit',
                                    'description' => $description . ' - Electronics Repayment',
                                    'transaction_date' => $transactionDate,
                                    'processed_by' => Auth::id(),
                                ]);

                                // 3) Also add a general transaction entry so it shows in user’s recent transactions
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
                            break;
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

        $loanPayments = LoanPayment::whereMonth('payment_date', $upload->month)
            ->whereYear('payment_date', $upload->year)
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
            'loanPayments' => $loanPayments,
            'commodityTransactions' => $commodityTransactions,
            'electronicsTransactions' => $electronicsTransactions,
            'loanInterestTransactions' => $loanInterestTransactions,
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

        // Security check: Only allow reversal of the most recent upload
        $latestUpload = MonthlyUpload::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
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
                $oldBalance = $user->shares_balance;

                // Reverse the transaction effect on balance
                if ($transaction->type === 'credit') {
                    $user->shares_balance -= $transaction->amount;
                } else {
                    $user->shares_balance += $transaction->amount;
                }

                $user->save();

                $reversalSummary['balance_adjustments'][] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'type' => 'shares',
                    'old_balance' => $oldBalance,
                    'new_balance' => $user->shares_balance,
                    'adjustment' => $user->shares_balance - $oldBalance
                ];

                $reversalSummary['users_affected'][] = $user->id;
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
                $oldBalance = $user->savings_balance;

                // Reverse the transaction effect on balance
                if ($transaction->type === 'credit') {
                    $user->savings_balance -= $transaction->amount;
                } else {
                    $user->savings_balance += $transaction->amount;
                }

                $user->save();

                $reversalSummary['balance_adjustments'][] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'type' => 'savings',
                    'old_balance' => $oldBalance,
                    'new_balance' => $user->savings_balance,
                    'adjustment' => $user->savings_balance - $oldBalance
                ];

                $reversalSummary['users_affected'][] = $user->id;
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
                $oldLoanBalance = $loan->remaining_balance;
                $oldUserLoanBalance = $user->loan_balance;

                // Reverse the payment effect on loan balance
                $loan->remaining_balance += $payment->amount;
                $user->loan_balance += $payment->amount;

                $loan->save();
                $user->save();

                $reversalSummary['balance_adjustments'][] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'type' => 'loan',
                    'old_balance' => $oldUserLoanBalance,
                    'new_balance' => $user->loan_balance,
                    'adjustment' => $user->loan_balance - $oldUserLoanBalance,
                    'loan_id' => $loan->id
                ];

                $reversalSummary['users_affected'][] = $user->id;
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
                $transaction->delete();
                $reversalSummary['commodities_deleted']++;
            }

            // 5. Reverse Electronics Transactions (NEW)
            $electronicsTransactions = Electronics::whereMonth('created_at', $upload->month)
                ->whereYear('created_at', $upload->year)
                ->where('description', 'like', '%MAB Electronics Repayment%')
                ->get();

            foreach ($electronicsTransactions as $electronics) {
                $reversalSummary['balance_adjustments'][] = [
                    'user_id' => $electronics->user_id,
                    'user_name' => $electronics->user->name ?? 'Unknown',
                    'type' => 'electronics',
                    'old_balance' => $electronics->amount,
                    'new_balance' => 0,
                    'adjustment' => -$electronics->amount
                ];

                $reversalSummary['users_affected'][] = $electronics->user_id;
                $electronics->delete();
                $reversalSummary['electronics_deleted']++;
            }

            // 6. Reverse Entrance Fee Transactions (NEW)
            $entranceTransactions = Transaction::where('type', 'entrance_fee')
                ->whereMonth('created_at', $upload->month)
                ->whereYear('created_at', $upload->year)
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

        // Add some sample data
        $sampleData = [
            [1, 'NSS/478', 'BANKOLE', 'EBENEZER OLUFEMI', '', 20000, '', '', '', '', '', '', 20000],
            [2, 'NSS/836', 'AWOSANYA', 'OLAWALE ADEDEJI', '', '', 60000, '', '', '', '', '', 60000],
            [3, 'NSS/412', 'ALUKO', 'OLUSEGUN O.', '', '', 50000, '', '', '', '', '', 50000],
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
     * Map column headers to their indices
     */
    private function mapColumns($headers)
    {
        $columnMap = [];

        // Convert headers to lowercase for case-insensitive matching
        $lowercaseHeaders = array_map('strtolower', $headers);

        // Map common column names to their indices
        $columnMappings = [
            'sno' => ['s/no', 'sno', 'serial', 'id'],
            'coopno' => ['coopno', 'coop no', 'cooperative number', 'member id'],
            'surname' => ['surname', 'last name', 'family name'],
            'othernames' => ['othernames', 'other names', 'first name', 'given name'],
            'entrance' => ['entrance', 'entrance fee', 'entrance_fee'],
            'shares' => ['shares', 'share'],
            'savings' => ['savings', 'saving'],
            'loan_repay' => ['loan repay', 'loan repayment', 'repayment'],
            'loan_int' => ['loan int', 'loan interest', 'interest'],
            'essential' => ['essential', 'essentials'],
            'non_essential' => ['non-essential', 'non essential', 'nonessential'],
            'electronics' => ['electronics', 'electronic'],
            'total' => ['total', 'total dec', 'total deduction', 'sum']
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
            }
        }

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
        $activeLoans = $user->loans()->where('status', 'active')->orderBy('created_at', 'asc')->get();

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
}
