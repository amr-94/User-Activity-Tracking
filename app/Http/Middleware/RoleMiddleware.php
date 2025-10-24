<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check user role
        switch ($role) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'Unauthorized - Admins only.');
                }
                break;

            case 'user':
                if (!$user->isUser()) {
                    abort(403, 'Unauthorized - Users only.');
                }
                break;

            default:
                abort(403, 'Unauthorized role access.');
        }

        return $next($request);
    }
}
