<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SystemUserController extends Controller
{
    /**
     * Display a listing of system users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $systemUsers = User::where('role', 'admin')->get();
        return view('admin.system_user', compact('systemUsers'));
    }

    /**
     * Store a newly created system user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'required|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'admin',
        ]);

        return redirect()->route('admin.system_users')->with('success', 'System user created successfully.');
    }

    /**
     * Update the specified system user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
        ]);

        $data = $request->only(['name', 'email', 'phone']);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.system_users')->with('success', 'System user updated successfully.');
    }

    /**
     * Remove the specified system user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting the last admin user
        $adminCount = User::where('role', 'admin')->count();
        if ($adminCount <= 1 && $user->role === 'admin') {
            return redirect()->route('admin.system_users')->with('error', 'Cannot delete the last admin user.');
        }

        $user->delete();

        return redirect()->route('admin.system_users')->with('success', 'System user deleted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Reset the user's password to a default value.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Set a default password (you might want to generate a random one)
        $defaultPassword = 'Password123!';
        $user->password = Hash::make($defaultPassword);
        $user->save();

        return redirect()->route('admin.system_users')->with('success', "Password reset successfully. New password: {$defaultPassword}");
    }
}
