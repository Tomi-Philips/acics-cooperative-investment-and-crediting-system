<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PendingMembershipController extends Controller
{
    /**
     * Display a listing of pending memberships.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get query parameters for filtering and sorting
        $search = $request->input('search');
        $status = $request->input('status', 'pending');
        $department = $request->input('department', 'all');
        $sort = $request->input('sort', 'newest');
        $perPage = $request->input('per_page', 10);

        // Start building the query
        // Note: In a real application, you would have a PendingMembership model
        // For now, we'll use the User model with a 'pending' status field
        $query = User::where('status', $status);

        // Apply search filter if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        // Apply department filter if provided and not 'all'
        if ($department !== 'all') {
            $query->where('department_id', $department);
        }

        // Apply sorting
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Get paginated results
        $pendingMemberships = $query->paginate($perPage);

        // Get counts for different statuses
        $pendingCount = User::where('status', 'pending')->count();
        $verifiedCount = User::where('status', 'verified')->count();
        $approvedCount = User::where('status', 'approved')->count();
        $rejectedCount = User::where('status', 'rejected')->count();

        // Get all departments for the filter dropdown
        $departments = Department::orderBy('title')->get();

        return view('admin.pending-memberships', [
            'pendingMemberships' => $pendingMemberships,
            'pendingCount' => $pendingCount,
            'verifiedCount' => $verifiedCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'departments' => $departments,
            'search' => $search,
            'status' => $status,
            'department' => $department,
            'sort' => $sort,
        ]);
    }

    /**
     * Display the specified pending membership.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $pendingMembership = User::findOrFail($id);

        return view('admin.view_pending_membership', [
            'pendingMembership' => $pendingMembership,
        ]);
    }

    /**
     * Mark a pending membership as verified.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($id)
    {
        $pendingMembership = User::findOrFail($id);

        // Update status to verified
        $pendingMembership->status = 'verified';
        $pendingMembership->verified_at = now();
        $pendingMembership->verified_by = Auth::id();
        $pendingMembership->save();

        // Log the verification
        Log::info('Membership application verified', [
            'admin_id' => Auth::id(),
            'user_id' => $pendingMembership->id,
            'user_email' => $pendingMembership->email,
        ]);
        
        // Send verification notification email
        try {
            $pendingMembership->notify(new \App\Notifications\ApplicationStatusNotification('verified', [
                'reference_number' => $pendingMembership->reference_number,
            ]));
            
            Log::info('Verification notification sent', [
                'user_id' => $pendingMembership->id,
                'user_email' => $pendingMembership->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification notification', [
                'error' => $e->getMessage(),
                'user_id' => $pendingMembership->id,
                'user_email' => $pendingMembership->email,
            ]);
            // Continue with the process even if notification fails
        }

        return redirect()->route('admin.pending-memberships')
            ->with('success', 'Membership application marked as verified.');
    }

    /**
     * Approve a pending membership.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $request, $id)
    {
        $pendingMembership = User::findOrFail($id);

        // Check if the membership is in a state that can be approved
        if ($pendingMembership->status !== 'pending' && $pendingMembership->status !== 'verified') {
            return redirect()->back()
                ->with('error', 'Only pending or verified applications can be approved.');
        }

        // Update status to approved
        $pendingMembership->status = 'approved';
        $pendingMembership->approved_at = now();
        $pendingMembership->approved_by = Auth::id();

        // Generate a temporary password if needed
        if (!$pendingMembership->password) {
            $tempPassword = Str::random(10);
            $pendingMembership->password = Hash::make($tempPassword);
            $pendingMembership->password_change_required = true;
        }

        $pendingMembership->save();

        // Generate password reset token for the user
        $token = Str::random(64);
        
        // Store the token in the password_reset_tokens table
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $pendingMembership->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );
        
        // Store token in session for direct access from status page
        session(['password_reset_token' => $token]);
        
        // Send approval notification with password set link
        if ($request->input('send_email', true)) {
            try {
                $pendingMembership->notify(new \App\Notifications\ApplicationStatusNotification('approved', [
                    'reference_number' => $pendingMembership->reference_number,
                    'token' => $token,
                    'email' => $pendingMembership->email,
                ]));
                
                Log::info('Approval notification sent', [
                    'user_id' => $pendingMembership->id,
                    'user_email' => $pendingMembership->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send approval notification', [
                    'error' => $e->getMessage(),
                    'user_id' => $pendingMembership->id,
                    'user_email' => $pendingMembership->email,
                ]);
                // Continue with the process even if notification fails
            }
        }

        // Log the approval
        Log::info('Membership application approved', [
            'admin_id' => Auth::id(),
            'user_id' => $pendingMembership->id,
            'user_email' => $pendingMembership->email,
        ]);

        return redirect()->route('admin.pending-memberships')
            ->with('success', 'Membership application approved successfully.');
    }

    /**
     * Reject a pending membership.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $pendingMembership = User::findOrFail($id);

        // Validate the request
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        // Update status to rejected
        $pendingMembership->status = 'rejected';
        $pendingMembership->rejected_at = now();
        $pendingMembership->rejected_by = Auth::id();
        $pendingMembership->rejection_reason = $validated['rejection_reason'];
        $pendingMembership->save();

        // Send rejection email if requested
        if ($request->input('send_email', true)) {
            try {
                $pendingMembership->notify(new \App\Notifications\ApplicationStatusNotification('rejected', [
                    'reference_number' => $pendingMembership->reference_number,
                    'rejection_reason' => $validated['rejection_reason'],
                ]));
                
                Log::info('Rejection notification sent', [
                    'user_id' => $pendingMembership->id,
                    'user_email' => $pendingMembership->email,
                    'reason' => $validated['rejection_reason'],
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send rejection notification', [
                    'error' => $e->getMessage(),
                    'user_id' => $pendingMembership->id,
                    'user_email' => $pendingMembership->email,
                ]);
                // Continue with the process even if notification fails
            }
        }

        // Log the rejection
        Log::info('Membership application rejected', [
            'admin_id' => Auth::id(),
            'user_id' => $pendingMembership->id,
            'user_email' => $pendingMembership->email,
            'reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.pending-memberships')
            ->with('success', 'Membership application rejected.');
    }
}
