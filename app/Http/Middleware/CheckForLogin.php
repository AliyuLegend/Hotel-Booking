<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckForLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // Check if the request URL matches 'admin/login'
    if ($request->is('admin/login')) {
        // Check if the authenticated admin user is set
        if (isset(Auth::guard('admin')->user()->name)) {
            // Redirect to the admin dashboard
            return redirect()->route('admins.dashboard');
        }
    }

    // Proceed with the next middleware
    return $next($request);
}


}