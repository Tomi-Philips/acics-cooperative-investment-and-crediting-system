<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Loan;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with dynamic data.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get total users count
        $totalUsers = User::where('role', 'member')->count();

        // Get departments count
        $departmentsCount = Department::count();

        // Get active loans count
        $activeLoans = Loan::whereIn('status', ['approved', 'disbursed'])->count();

        // Get pending loans count
        $pendingLoans = Loan::where('status', 'pending')->count();

        // Get total revenue (interest earned from loans)
        $totalRevenue = Loan::whereIn('status', ['approved', 'disbursed', 'completed'])
            ->sum(DB::raw('total_payment - amount'));

        // Get inactive users count
        $inactiveUsers = User::where('role', 'member')
            ->where(function($query) {
                $query->whereNull('email_verified_at')
                    ->orWhere('status', '!=', 'active');
            })->count();

        // Get pending feedback count (using tickets as feedback)
        $pendingFeedback = DB::table('tickets')->where('status', 'open')->count();

        // Get recent transactions using the new grouping system with pagination (5 for dashboard)
        $transactionGroupService = new TransactionGroupService();
        $recentTransactions = $transactionGroupService->getGroupedTransactionsForDashboard(5);



        // Get new users registered in the last 30 days
        $newUsers = User::where('created_at', '>=', now()->subDays(30))
                        ->where('role', 'member')
                        ->count();

        // Get user registration data for chart (last 7 days)
        $userRegistrationData = User::where('created_at', '>=', now()->subDays(7))
                                    ->where('role', 'member')
                                    ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                    ->groupBy('date')
                                    ->orderBy('date')
                                    ->get()
                                    ->keyBy('date')
                                    ->map(function ($item) {
                                        return $item->count;
                                    })
                                    ->toArray();

        // Fill in missing dates with zero counts
        $dateRange = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dateRange[$date] = $userRegistrationData[$date] ?? 0;
        }

        // Get loan data for chart (last 7 days)
        $loanData = Loan::where('created_at', '>=', now()->subDays(7))
                        ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total_amount')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get()
                        ->keyBy('date');

        $loanCounts = [];
        $loanAmounts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $loanCounts[$date] = $loanData->get($date)->count ?? 0;
            $loanAmounts[$date] = $loanData->get($date)->total_amount ?? 0;
        }

        // Get transaction data by type for pie chart
        $transactionsByType = Transaction::selectRaw('type, COUNT(*) as count, SUM(amount) as total_amount')
                                        ->groupBy('type')
                                        ->get()
                                        ->mapWithKeys(function ($item) {
                                            return [$item->type => [
                                                'count' => $item->count,
                                                'amount' => $item->total_amount
                                            ]];
                                        });

        // Get monthly financial summary (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('Y-m');
            $monthLabel = $month->format('M Y');

            $monthlyData[$monthKey] = [
                'label' => $monthLabel,
                'loans' => Loan::whereYear('created_at', $month->year)
                              ->whereMonth('created_at', $month->month)
                              ->sum('amount'),
                'transactions' => Transaction::whereYear('created_at', $month->year)
                                           ->whereMonth('created_at', $month->month)
                                           ->sum('amount'),
                'users' => User::where('role', 'member')
                              ->whereYear('created_at', $month->year)
                              ->whereMonth('created_at', $month->month)
                              ->count()
            ];
        }

        return view('admin.index', [
            'totalUsers' => $totalUsers,
            'departmentsCount' => $departmentsCount,
            'activeLoans' => $activeLoans,
            'pendingLoans' => $pendingLoans,
            'totalRevenue' => $totalRevenue,
            'inactiveUsers' => $inactiveUsers,
            'pendingFeedback' => $pendingFeedback,
            'recentTransactions' => $recentTransactions,
            'newUsers' => $newUsers,
            'userRegistrationData' => json_encode(array_values($dateRange)),
            'dateLabels' => json_encode(array_keys($dateRange)),
            'loanCountData' => json_encode(array_values($loanCounts)),
            'loanAmountData' => json_encode(array_values($loanAmounts)),
            'transactionsByType' => json_encode($transactionsByType),
            'monthlyData' => json_encode($monthlyData),
        ]);
    }
}
