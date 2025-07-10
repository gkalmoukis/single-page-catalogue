<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = null;

        // Only support path-based tenant resolution (e.g., localhost:8000/t/pizza-palace)
        $path = $request->path();
        if (str_starts_with($path, 't/')) {
            $pathSegments = explode('/', $path);
            if (count($pathSegments) >= 2) {
                $tenantSlug = $pathSegments[1];
                $tenant = Tenant::where('slug', $tenantSlug)->where('is_active', true)->first();
                
                // If tenant not found, return 404
                if (!$tenant) {
                    abort(404, 'Restaurant not found');
                }
            }
        }

        // Bind the tenant to the container
        app()->instance('current_tenant', $tenant);

        return $next($request);
    }
}
