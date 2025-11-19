<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApplicationStatusController extends Controller
{
    /**
     * Show the application status check form.
     *
     * @return \Illuminate\View\View
     */
    public function showStatusCheckForm()
    {
        return view('auth.application_status_check');
    }
    
    /**
     * Check application status by email (GET route).
     *
     * @param  string  $email
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function checkStatusByEmail($email, Request $request)
    {
        // Validate the email parameter
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);
        
        // Get reference number from query parameters if provided
        $referenceNumber = $request->query('reference');

        if ($validator->fails()) {
            return redirect()->route('application.status')
                ->withErrors(['email' => 'Invalid email format.']);
        }

        Log::info('Checking application status by email', [
            'email' => $email,
            'reference_number' => $referenceNumber ?: 'not provided'
        ]);

        try {
            // Build query based on email and optional reference number
            $query = User::where('email', $email);
            
            // Add reference number to query if provided
            if (!empty($referenceNumber)) {
                $query->where('reference_number', $referenceNumber);
            }
            
            // Find the user(s)
            $user = $query->first();

            if (!$user) {
                $errorMessage = 'No application found with this email';
                if (!empty($referenceNumber)) {
                    $errorMessage .= ' and reference number';
                }
                $errorMessage .= '.';
                
                return redirect()->route('application.status')
                    ->withErrors(['general' => $errorMessage]);
            }

            // If user exists but is a regular member without a membership application (not pending/verified)
            if ($user->role === 'member' && $user->status === 'approved' && !$user->member) {
                return redirect()->route('application.status')
                    ->withErrors(['general' => 'You are already an approved member. Please log in to access your account.']);
            }

            // Get the member associated with this user
            $member = $user->member;

            // Check if password token is available for approved users
            $passwordToken = null;
            if ($user->status === 'approved') {
                // Always generate a new token for the user
                $passwordToken = \Illuminate\Support\Str::random(64);
                
                // Update or insert the token
                \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'token' => $passwordToken,
                        'created_at' => now()
                    ]
                );
                
                // Store in session for the view to access
                session(['password_reset_token' => $passwordToken]);
                
                // Log the token for debugging
                \Illuminate\Support\Facades\Log::info('Set password token generated', [
                    'email' => $user->email,
                    'token' => $passwordToken,
                    'reset_link' => route('set_password', ['token' => $passwordToken, 'email' => $user->email])
                ]);
            }
            
            // Prepare data for the view
            $applicationData = [
                'reference_number' => $user->reference_number,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'application_date' => $user->created_at->format('F j, Y, g:i a'),
                'status_history' => $this->getStatusHistory($user),
                'department' => $user->department ? $user->department->title : 'N/A',
                'staff_id' => $user->member_number ?? 'N/A',
                'phone' => $member ? $member->phone : 'N/A',
                'password_token' => $passwordToken
            ];

            // Log the status check
            Log::info('Application status checked by email', [
                'email' => $email,
                'status' => $user->status,
            ]);

            return view('auth.application_status_result', compact('applicationData'));
        } catch (\Exception $e) {
            Log::error('Error checking application status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('application.status')
                ->withErrors(['general' => 'An error occurred while checking your application status. Please try again later.']);
        }
    }

    /**
     * Check the application status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function checkStatus(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'reference_number' => 'nullable|string',
        ]);

        
        // Log the request for debugging
        Log::info('Application status check request', [
            'email' => $validated['email'],
            'reference_number' => $validated['reference_number'] ?? null,
            'request_path' => $request->path(),
            'request_method' => $request->method()
        ]);
            
        try {
            // Find user by email only (reference number is optional now)
            $query = User::where('email', $validated['email']);
            
            // Add reference number to query if provided
            if (!empty($validated['reference_number'])) {
                $query->where('reference_number', $validated['reference_number']);
            }
            
            $user = $query->first();

            if (!$user) {
                return back()->withErrors([
                    'general' => 'No application found with the provided email address.'
                ]);
            }
            
            // If user exists but is a regular member without a membership application (not pending/verified)
            if ($user->role === 'member' && $user->status === 'approved' && !$user->member) {
                return back()->withErrors([
                    'general' => 'You are already an approved member. Please log in to access your account.'
                ]);
            }

            // Get the member associated with this user
            $member = $user->member;

            // Prepare data for the view
            $applicationData = [
                'reference_number' => $user->reference_number,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
                'application_date' => $user->created_at->format('F j, Y, g:i a'),
                'status_history' => $this->getStatusHistory($user),
                'department' => $user->department ? $user->department->title : 'N/A',
                'staff_id' => $user->member_number ?? 'N/A',
                'phone' => $member ? $member->phone : 'N/A',
            ];

            // Log the status check
            Log::info('Application status checked', [
                'reference_number' => $validated['reference_number'],
                'email' => $validated['email'],
                'status' => $user->status,
            ]);

            return view('auth.application_status_result', compact('applicationData'));
        } catch (\Exception $e) {
            Log::error('Error checking application status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'general' => 'An error occurred while checking your application status. Please try again later.'
            ]);
        }
    }

    /**
     * Get status history for a user based on timestamps.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    private function getStatusHistory(User $user)
    {
        $history = [];

        $history[] = [
            'status' => 'Submitted',
            'date' => $user->created_at->format('F j, Y'),
            'description' => 'Application submitted successfully.',
            'completed' => true,
            'label' => 'Submitted'
        ];

        // Add Verified step
        $history[] = [
            'status' => 'Verified',
            'date' => $user->verified_at ? $user->verified_at->format('F j, Y') : null,
            'description' => $user->verified_at ? 'Documents verified. Pending committee approval.' : 'Pending document verification.',
            'completed' => $user->verified_at ? true : false,
            'label' => 'Verified'
        ];

        $history[] = [
            'status' => 'Approved',
            'date' => $user->approved_at ? $user->approved_at->format('F j, Y') : null,
            'description' => $user->approved_at ? 'Membership approved. You can now set your password and login.' : 'Pending committee approval.',
            'completed' => $user->approved_at ? true : false,
            'label' => 'Approved'
        ];

        if ($user->rejected_at) {
            $history[] = [
                'status' => 'Rejected',
                'date' => $user->rejected_at->format('F j, Y'),
                'description' => 'Application rejected: ' . ($user->rejection_reason ?? 'No reason provided.'),
                'completed' => true,
                'rejected' => true
            ];
        }

        return $history;
    }
}