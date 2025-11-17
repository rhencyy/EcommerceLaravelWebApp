<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate_Support_Facades_Auth; // <-- Import this

class CheckIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is logged in AND is an admin
        if (Auth::check() && Auth::user()->is_admin == 1) {
            // 2. If yes, continue to the requested page
            return $next($request);
        }

        // 3. If no, redirect them to the regular user dashboard
        return redirect('/dashboard')->with('error', 'You do not have admin access.');
    }
}
