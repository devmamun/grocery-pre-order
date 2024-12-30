<?php

namespace Mamun\ShopPreOrder\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $limit
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $rateLimitAttempts = 3)
    {
        $rateLimitKey = 'preorder.' . $request->ip();
        $rateLimitTimeout = 60; // Lockout time in seconds

        // Apply rate limiting
        if (RateLimiter::tooManyAttempts($rateLimitKey, $rateLimitAttempts)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Too many attempts. Please try again later.',
            ], 429);
        }

        // Log the attempt
        RateLimiter::hit($rateLimitKey, $rateLimitTimeout);

        return $next($request);
    }
}
