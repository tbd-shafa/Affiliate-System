<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        
        if (Auth::check()) {
            $user = Auth::user();
            // Check if the user has any of the required roles
            if ($user->roles()->whereIn('name', $roles)->exists()) {
                return $next($request);
            }
        }

        abort(403, 'Access Denied');
    }
}
