<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Add this line
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'login_identifier' => 'required|string',
            'password' => 'required',
        ]);

        $loginIdentifier = $request->input('login_identifier');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $fieldType = filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'member_number';

        $credentials = [
            $fieldType => $loginIdentifier,
            'password' => $password,
        ];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Log successful login
            Log::info('User logged in successfully', ['user_id' => Auth::id(), 'identifier' => $loginIdentifier]);

            // Redirect based on user role
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('user.dashboard'));
            }
        }

        // Log failed login attempt
        Log::warning('Failed login attempt', ['identifier' => $loginIdentifier, 'ip' => $request->ip()]);

        throw ValidationException::withMessages([
            'login_identifier' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Log the logout
        if (Auth::check()) {
            Log::info('User logged out', ['user_id' => Auth::id()]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Show the password reset request form.
     *
     * @return \Illuminate\View\View
     */
    public function showResetRequestForm()
    {
        return view('auth.reset');
    }

    /**
     * Process the password reset request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processResetRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate a unique token
        $token = Str::random(64);

        // Store the token in the password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Send the password reset email
        try {
            \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\PasswordReset(
                $token, 
                $request->email,
                route('set_password', ['token' => $token, 'email' => $request->email])
            ));
            
            Log::info('Password reset email sent', [
                'email' => $request->email,
                'token' => $token,
                'reset_link' => route('set_password', ['token' => $token, 'email' => $request->email])
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);
            // Continue with success message even if email fails
        }

        // Redirect back with a success message
        return back()->with('status', 'Password reset link has been sent to your email address.');
    }

    /**
     * Show the password reset form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showSetPasswordForm(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        // Validate token exists for this email
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('reset')
                ->withErrors(['email' => 'Invalid or expired password reset link.']);
        }

        return view('auth.set_password', compact('token', 'email'));
    }

    /**
     * Process the password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processSetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Validate token exists for this email
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('reset')
                ->withErrors(['email' => 'Invalid or expired password reset link.']);
        }

        // Update the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->password_change_required = false;
        $user->save();

        // Delete the token
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Log the password reset
        Log::info('Password reset completed', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        // Log the user in
        Auth::login($user);

        // Redirect based on user role
        if (Auth::user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        } else {
            return redirect()->intended(route('user.dashboard'));
        }
    }

    /**
     * Show the membership registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showMembershipRegistrationForm()
    {
        // Get only active departments for the dropdown
        $departments = \App\Models\Department::where('is_active', true)->orderBy('title')->get();
        
        // Debug: Log departments being passed to view
        \Illuminate\Support\Facades\Log::info('Departments for registration form', [
            'count' => $departments->count(),
            'departments' => $departments->pluck('title', 'id')->toArray()
        ]);

        // Get relationship options for next of kin
        $relationships = [
            'spouse' => 'Spouse',
            'parent' => 'Parent',
            'child' => 'Child',
            'sibling' => 'Sibling',
            'relative' => 'Other Relative',
            'friend' => 'Friend',
            'colleague' => 'Colleague'
        ];

        return view('auth.membership_registration', compact('departments', 'relationships'));
    }

    /**
     * Process the membership registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processMembershipRegistration(Request $request)
    {
        // Log the request data for debugging
        Log::info('Membership registration request received', [
            'request_data' => $request->except(['password', 'password_confirmation']),
            'has_terms' => $request->has('terms'),
            'terms_value' => $request->input('terms'),
        ]);

        try {
            // Validate the registration data
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'date_of_birth' => 'required|date|before:today',
                'gender' => 'required|in:male,female,other',
                'department' => 'required|exists:departments,id',
                'staff_id' => 'required|string|max:50|unique:users,member_number',
                'position' => 'required|string|max:100',
                'employment_date' => 'required|date|before_or_equal:today',
                'next_of_kin_name' => 'required|string|max:255',
                'next_of_kin_relationship' => 'required|string|max:50',
                'next_of_kin_phone' => 'required|string|max:20',
                'next_of_kin_address' => 'required|string|max:255',
                'terms' => 'required|accepted',
            ]);

            // Generate a unique reference number
            $referenceNumber = 'ACICS-' . date('Ymd') . '-' . rand(1000, 9999);

            // Create a temporary password
            $tempPassword = Str::random(10);

            // Verify that department exists before creating user
            $department = \App\Models\Department::find($validated['department']);
            if (!$department) {
                return back()
                    ->withInput()
                    ->withErrors(['department' => 'Selected department does not exist or is not active.']);
            }

            // Debug information about department
            Log::info('Department info for registration', [
                'department_id' => $validated['department'],
                'department_found' => $department ? true : false,
                'department_title' => $department ? $department->title : null,
                'department_active' => $department ? $department->is_active : null
            ]);

            // Start a database transaction
            DB::beginTransaction();

            try {
                // Create the user with pending status
                $user = User::create([
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'department_id' => $validated['department'],
                    'member_number' => $validated['staff_id'],
                    'password' => Hash::make($tempPassword),
                    'role' => 'member',
                    'status' => 'pending',
                    'reference_number' => $referenceNumber,
                    'password_change_required' => true,
                    'is_active' => true, // Ensure account is active by default
                ]);

                // Create member profile with additional details
                $member = new \App\Models\Member([
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'gender' => $validated['gender'],
                    'position' => $validated['position'],
                    'joined_at' => $validated['employment_date'],
                    'next_of_kin_name' => $validated['next_of_kin_name'],
                    'next_of_kin_relationship' => $validated['next_of_kin_relationship'],
                    'next_of_kin_phone' => $validated['next_of_kin_phone'],
                    'next_of_kin_address' => $validated['next_of_kin_address'],
                    'status' => 'pending',
                    'member_number' => $validated['staff_id'], // Add member_number from staff_id
                ]);

                $user->member()->save($member);

                // Commit the transaction
                DB::commit();

                // Store the reference number in the session for display on the success page
                session(['reference_number' => $referenceNumber]);

                // Log the registration
                Log::info('New membership registration', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'reference_number' => $referenceNumber,
                    'temp_password' => $tempPassword, // Only log this in development
                ]);
                
                // Send application submitted notification email
                try {
                    $user->notify(new \App\Notifications\ApplicationStatusNotification('submitted', [
                        'reference_number' => $referenceNumber,
                    ]));
                } catch (\Exception $e) {
                    Log::error('Failed to send application notification email', [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id,
                        'email' => $user->email
                    ]);
                    // Continue with the process even if email sending fails
                }

                // Redirect to success page
                return redirect()->route('membership_success');
            } catch (\Exception $e) {
                // Rollback the transaction if an error occurs during user creation
                DB::rollBack();
                throw $e; // Re-throw to be caught by the outer catch block
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors - let Laravel handle this automatically
            throw $e;
        } catch (\Exception $e) {
            // Log the detailed error for debugging
            Log::error('Membership registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);

            // Redirect back with a generic error message
            return back()
                ->withInput()
                ->withErrors(['general' => 'An error occurred while processing your registration. Please try again later or contact support.']);
        }
    }
}
