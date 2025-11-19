<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if user has the required role
        if ($role === 'admin' && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action. This area is restricted to administrators only.');
        }

        if ($role === 'member' && !Auth::user()->isMember()) {
            abort(403, 'Unauthorized action. This area is restricted to members only.');
        }

        return $next($request);
    }
}
