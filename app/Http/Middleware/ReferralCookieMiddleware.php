<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


class ReferralCookieMiddleware
{
    /**
     * Handle an incoming request.
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     // Check if the request has a referrer query parameter
    //     $referrerCode = $request->query('referrer') ?: ''; 

    //     if (!empty($referrerCode)) {
    //         // Set the referral cookie for 1 day (24 hours)
    //         Cookie::queue('referrer_code', $referrerCode, 60 * 24);
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, $expirationTime = 10080) // Default to 1440 minutes (24 hours)
    {
     
        // Check if the request has a referrer query parameter
        $referrerCode = $request->query('referrer') ?: '';

        if (!empty($referrerCode)) {
            // Set the referral cookie with a dynamic expiration time (default is 24 hours)
            Cookie::queue('referrer_code', $referrerCode, $expirationTime);
        }

        return $next($request);
    }
}

