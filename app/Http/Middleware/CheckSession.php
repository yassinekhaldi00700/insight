<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('login') || $request->is('register') || $request->is('password/*')) {
            return $next($request);
        }

        if (!session()->has('user_id')) {
            return redirect()->route('login')->withErrors(['message' => 'Your session has expired. Please log in again.']);
        }

        $response = $next($request);

        // Prevent caching to block access to restricted pages after logout/session expiry
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}

