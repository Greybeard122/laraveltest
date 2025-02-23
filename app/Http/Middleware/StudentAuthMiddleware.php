<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StudentAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Middleware Check: Student Authenticated?', [
            'authenticated' => Auth::guard('student')->check(),
            'user' => Auth::guard('student')->user()
        ]);

        if (!Auth::guard('student')->check()) {
            Log::warning('Middleware Redirect: Student Not Authenticated');
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        return $next($request);
    }
}
