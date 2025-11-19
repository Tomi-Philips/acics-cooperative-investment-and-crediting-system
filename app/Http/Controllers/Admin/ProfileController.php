<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\MonthlyUpload;
use App\Models\AvailableCommodity;

class ProfileController extends Controller
{
    /**
     * Display the admin profile.
     */
    public function index()
    {
        $admin = Auth::user();
        
        // Get recent admin activities
        $recentActivities = $this->getRecentActivities();
        
        return view('admin.profile', compact('admin', 'recentActivities'));
    }

    /**
     * Show the edit profile form.
     */
    public function edit()
    {
        $admin = Auth::user();
        
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update the admin profile.
     */
    public function update(Request $request)
    {
        $admin = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show the change password form.
     */
    public function showChangePasswordForm()
    {
        return view('admin.profile.change-password');
    }

    /**
     * Update the admin password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Auth::user();

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile')->with('success', 'Password updated successfully!');
    }

    /**
     * Get recent admin activities.
     */
    private function getRecentActivities()
    {
        $activities = collect();

        try {
            // Get recent manual transactions processed by this admin
            $recentTransactions = \App\Models\TransactionGroup::with('transactions.user')
                ->where('processed_by', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            foreach ($recentTransactions as $group) {
                $transactionCount = $group->total_records ?? 1;
                $totalAmount = $group->total_amount ?? 0;

                $activities->push([
                    'type' => 'transaction',
                    'description' => "Processed {$transactionCount} transaction(s) totaling ₦" . number_format($totalAmount, 2),
                    'time' => $group->created_at,
                    'icon' => 'credit-card',
                    'color' => 'blue',
                    'status' => 'completed'
                ]);
            }

            // Get recent monthly uploads
            $recentUploads = MonthlyUpload::with('uploader')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            foreach ($recentUploads as $upload) {
                $activities->push([
                    'type' => 'upload',
                    'description' => "Processed MAB upload for {$upload->formatted_date}",
                    'time' => $upload->created_at,
                    'icon' => 'upload',
                    'color' => 'blue',
                    'status' => $upload->status ?? 'unknown'
                ]);
            }

            // Get recent commodity additions (if table exists)
            if (DB::getSchemaBuilder()->hasTable('available_commodities')) {
                $recentCommodities = AvailableCommodity::orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();

                foreach ($recentCommodities as $commodity) {
                    $activities->push([
                        'type' => 'commodity',
                        'description' => "Added commodity: {$commodity->name}",
                        'time' => $commodity->created_at,
                        'icon' => 'package',
                        'color' => 'green',
                        'status' => 'active'
                    ]);
                }
            }

            // Get recent loan approvals/disbursements
            $recentLoans = \App\Models\Loan::where('approved_by', Auth::id())
                ->orWhere('disbursed_by', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            foreach ($recentLoans as $loan) {
                $action = $loan->disbursed_at ? 'disbursed' : 'approved';
                $activities->push([
                    'type' => 'loan',
                    'description' => "Loan {$action}: {$loan->loan_number} - ₦" . number_format($loan->amount, 2),
                    'time' => $loan->created_at,
                    'icon' => 'dollar-sign',
                    'color' => 'green',
                    'status' => $loan->status
                ]);
            }

            // Get recent user registrations
            $recentUsers = User::where('role', 'member')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            foreach ($recentUsers as $user) {
                $activities->push([
                    'type' => 'user',
                    'description' => "New user registered: {$user->name}",
                    'time' => $user->created_at,
                    'icon' => 'user-plus',
                    'color' => 'purple',
                    'status' => $user->status ?? 'active'
                ]);
            }

            // Get recent saving withdrawals processed
            $recentWithdrawals = \App\Models\SavingWithdrawal::where('processed_by', Auth::id())
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            foreach ($recentWithdrawals as $withdrawal) {
                $activities->push([
                    'type' => 'withdrawal',
                    'description' => "Processed withdrawal request: ₦" . number_format($withdrawal->amount, 2),
                    'time' => $withdrawal->created_at,
                    'icon' => 'arrow-down',
                    'color' => 'orange',
                    'status' => $withdrawal->status
                ]);
            }

            // Add some system activities if no other activities exist
            if ($activities->isEmpty()) {
                $activities->push([
                    'type' => 'system',
                    'description' => 'System is running smoothly',
                    'time' => now(),
                    'icon' => 'check',
                    'color' => 'green',
                    'status' => 'active'
                ]);
            }

        } catch (\Exception $e) {
            // Log the error but don't break the page
            Log::warning('Error fetching recent activities: ' . $e->getMessage());

            // Return a default activity
            $activities->push([
                'type' => 'system',
                'description' => 'Unable to load recent activities',
                'time' => now(),
                'icon' => 'exclamation',
                'color' => 'orange',
                'status' => 'warning'
            ]);
        }

        // Sort by time and take the most recent 10
        return $activities->sortByDesc('time')->take(10);
    }
}
