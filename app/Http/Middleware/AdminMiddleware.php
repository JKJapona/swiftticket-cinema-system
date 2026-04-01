<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /*
    |--------------------------------------------------------------------------
    | Administrator Access Verification
    |--------------------------------------------------------------------------
    */

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        if (Auth::check()) {
            abort(403, 'Unauthorized access.'); 
        }

        return redirect()->route('login');
    }
}