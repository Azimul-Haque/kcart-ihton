<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class GlobalOTPThrottle
{
    public function handle(Request $request, Closure $next): Response
    {
        // 🌟 Define the key based ONLY on the IP address
        // This ensures the block applies to ALL mobile numbers from this IP.
        $key = 'global_otp_limit:' . $request->ip();

        // 🛡️ Block if more than 1 request in 60 seconds (Strict Cooldown)
        if (RateLimiter::tooManyAttempts($key, $maxAttempts = 1)) {
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'success' => false,
                'message' => "Too many attempts from this network. Please wait $seconds seconds."
            ], 429);
        }

        // Increment the attempt and set the cooldown for 60 seconds
        RateLimiter::hit($key, 60);

        return $next($request);
    }
}