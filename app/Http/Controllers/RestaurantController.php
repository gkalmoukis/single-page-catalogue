<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Support\Facades\Cache;

class RestaurantController extends Controller
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index()
    {
        // Main landing page - redirect to admin
        return redirect('/admin');
    }

    public function show(Request $request)
    {
        $tenant = $this->tenantService->getCurrentTenant();
        
        // If no tenant found, 404 will be handled by middleware
        if (!$tenant) {
            abort(404, 'Restaurant not found');
        }

        $cacheKey = config('default.catalogue_cache_key') . '_' . $tenant->id;
        
        return view('restaurant.show', [
            'tenant' => $tenant,
            'categories' => Cache::remember(
                $cacheKey, 
                config('default.catalogue_cache_ttl'), 
                function () use ($tenant) {
                    return Category::forTenant($tenant->id)
                        ->with([
                            'items' => fn ($query) => $query->active()
                                ->forTenant($tenant->id)
                                ->with('tags')
                                ->orderBy('sort_order')
                        ])
                        ->orderBy('sort_order')
                        ->get();
                }
            )
        ]);
    }
}
