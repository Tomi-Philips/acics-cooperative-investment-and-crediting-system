<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get summary statistics
        $stats = [
            'total_users' => User::where('role', 'member')->count(),
            'total_loans' => Loan::count(),
            'total_loan_amount' => Loan::sum('amount'),
            'total_transactions' => Transaction::count(),
        ];
        
        // Get recent transactions
        $recentTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get loan distribution by status
        $loansByStatus = Loan::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
        
        // Get transaction distribution by type
        $transactionsByType = Transaction::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();
        
        return view('admin.reports', compact('stats', 'recentTransactions', 'loansByStatus', 'transactionsByType'));
    }
    
    /**
     * Generate a user report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function users(Request $request)
    {
        $users = User::where('role', 'member')
            ->when($request->has('search'), function($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.reports.users', compact('users'));
    }
    
    /**
     * Generate a loan report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function loans(Request $request)
    {
        $loans = Loan::with('user')
            ->when($request->has('status'), function($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->when($request->has('start_date') && $request->has('end_date'), function($query) use ($request) {
                return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.reports.loans', compact('loans'));
    }
    
    /**
     * Generate a transaction report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function transactions(Request $request)
    {
        $transactions = Transaction::with('user')
            ->when($request->has('type'), function($query) use ($request) {
                return $query->where('type', $request->type);
            })
            ->when($request->has('start_date') && $request->has('end_date'), function($query) use ($request) {
                return $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.reports.transactions', compact('transactions'));
    }
    
    /**
     * Export report data to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $type = $request->type ?? 'transactions';
        $filename = $type . '_report_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($type, $request) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'users') {
                fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Joined Date']);
                
                User::where('role', 'member')
                    ->chunk(100, function($users) use ($file) {
                        foreach ($users as $user) {
                            fputcsv($file, [
                                $user->id,
                                $user->name,
                                $user->email,
                                $user->phone,
                                $user->created_at->format('Y-m-d'),
                            ]);
                        }
                    });
            } elseif ($type === 'loans') {
                fputcsv($file, ['ID', 'User', 'Amount', 'Status', 'Created Date']);
                
                Loan::with('user')
                    ->when($request->has('status'), function($query) use ($request) {
                        return $query->where('status', $request->status);
                    })
                    ->chunk(100, function($loans) use ($file) {
                        foreach ($loans as $loan) {
                            fputcsv($file, [
                                $loan->id,
                                $loan->user->name ?? 'Unknown',
                                $loan->amount,
                                $loan->status,
                                $loan->created_at->format('Y-m-d'),
                            ]);
                        }
                    });
            } else {
                fputcsv($file, ['ID', 'User', 'Type', 'Amount', 'Reference', 'Created Date']);
                
                Transaction::with('user')
                    ->when($request->has('type'), function($query) use ($request) {
                        return $query->where('type', $request->type);
                    })
                    ->chunk(100, function($transactions) use ($file) {
                        foreach ($transactions as $transaction) {
                            fputcsv($file, [
                                $transaction->id,
                                $transaction->user->name ?? 'Unknown',
                                $transaction->type,
                                $transaction->amount,
                                $transaction->reference,
                                $transaction->created_at->format('Y-m-d'),
                            ]);
                        }
                    });
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
