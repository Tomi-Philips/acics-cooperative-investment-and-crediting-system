<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyUpload;
use App\Models\ShareTransaction;
use App\Models\SavingTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SimpleBulkController extends Controller
{
    public function testUpload(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('admin.simple_bulk_test');
        }

        // Validate request
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
            'transaction_date' => 'required|date',
            'description' => 'required|string',
        ]);

        Log::info('Simple bulk upload started', [
            'file' => $request->file('excel_file')->getClientOriginalName(),
            'date' => $request->transaction_date,
            'description' => $request->description
        ]);

        try {
            // Process Excel file
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $highestRow = $worksheet->getHighestRow();

            Log::info('Excel file loaded', ['rows' => $highestRow]);

            // Create monthly upload record
            $transactionDate = Carbon::parse($request->transaction_date);
            $monthlyUpload = MonthlyUpload::create([
                'year' => $transactionDate->year,
                'month' => $transactionDate->month,
                'upload_type' => 'financial_records',
                'file_name' => $file->getClientOriginalName(),
                'total_records' => $highestRow - 2, // Excluding header rows
                'processed_records' => 0,
                'failed_records' => 0,
                'update_fields' => ['shares', 'savings'],
                'description' => $request->description,
                'status' => 'processing',
                'uploaded_by' => Auth::id(),
                'upload_started_at' => now(),
            ]);

            Log::info('Monthly upload record created', ['id' => $monthlyUpload->id]);

            DB::beginTransaction();

            $processed = 0;
            $failed = 0;

            // Process data rows (starting from row 3)
            for ($row = 3; $row <= $highestRow; $row++) {
                $coopno = $worksheet->getCell('B' . $row)->getCalculatedValue();
                $shares = (float) $worksheet->getCell('F' . $row)->getCalculatedValue();
                $savings = (float) $worksheet->getCell('G' . $row)->getCalculatedValue();

                if (empty($coopno)) continue;

                // Find user
                $user = User::whereHas('member', function($query) use ($coopno) {
                    $query->where('member_number', $coopno);
                })->first();

                if (!$user) {
                    Log::warning('User not found', ['member_number' => $coopno]);
                    $failed++;
                    continue;
                }

                // Process shares
                if ($shares > 0) {
                    ShareTransaction::create([
                        'user_id' => $user->id,
                        'amount' => $shares,
                        'type' => 'credit',
                        'description' => $request->description,
                        'transaction_date' => $transactionDate,
                    ]);

                    $user->member->increment('total_share_amount', $shares);
                    Log::info('Share transaction created', [
                        'user' => $user->name,
                        'amount' => $shares
                    ]);
                }

                // Process savings
                if ($savings > 0) {
                    SavingTransaction::create([
                        'user_id' => $user->id,
                        'amount' => $savings,
                        'type' => 'credit',
                        'description' => $request->description,
                        'transaction_date' => $transactionDate,
                    ]);

                    $user->member->increment('total_saving_amount', $savings);
                    Log::info('Saving transaction created', [
                        'user' => $user->name,
                        'amount' => $savings
                    ]);
                }

                $processed++;
            }

            DB::commit();

            // Update monthly upload record
            $monthlyUpload->update([
                'processed_records' => $processed,
                'failed_records' => $failed,
                'status' => 'completed',
                'upload_completed_at' => now(),
                'processing_summary' => [
                    'total_processed' => $processed,
                    'total_failed' => $failed,
                    'processing_time' => now()->diffInSeconds($monthlyUpload->upload_started_at)
                ]
            ]);

            Log::info('Upload completed successfully', [
                'processed' => $processed,
                'failed' => $failed
            ]);

            return redirect()->back()->with('success', 
                "Upload completed successfully! Processed: $processed, Failed: $failed"
            );

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Upload failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->back()->with('error', 
                'Upload failed: ' . $e->getMessage()
            );
        }
    }
}
