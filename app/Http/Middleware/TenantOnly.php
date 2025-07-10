<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class TenantOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('filament.tenant.auth.login');
        }

        // Check if user has access to at least one tenant (non-admin users)
        if (!Auth::user()->is_admin && Auth::user()->tenants->isEmpty()) {
            abort(403, 'Access denied. You must be assigned to at least one tenant.');
        }

        return $next($request);
    }
}