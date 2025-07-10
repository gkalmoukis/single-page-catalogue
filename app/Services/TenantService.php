<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;

class TenantService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getCurrentTenant(): ?Tenant
    {
        // Check if current_tenant is bound in the container (set by ResolveTenant middleware)
        if (app()->bound('current_tenant')) {
            return app('current_tenant');
        }
        
        // Return null if not in a web request context or middleware hasn't run yet
        return null;
    }

    public function createTenant(array $data, User $owner): Tenant
    {
        $tenant = Tenant::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?? Str::slug($data['name']),
            'domain' => $data['domain'] ?? null,
            'data' => $data['data'] ?? [],
            'is_active' => true,
        ]);

        // Attach the owner to the tenant
        $tenant->users()->attach($owner->id, ['role' => 'owner']);
        
        // Set as current tenant for the owner
        $owner->update(['current_tenant_id' => $tenant->id]);

        return $tenant;
    }

    public function addUserToTenant(Tenant $tenant, User $user, string $role = 'admin'): void
    {
        $tenant->users()->syncWithoutDetaching([$user->id => ['role' => $role]]);
    }

    public function removeUserFromTenant(Tenant $tenant, User $user): void
    {
        $tenant->users()->detach($user->id);
        
        // If this was their current tenant, clear it
        if ($user->current_tenant_id === $tenant->id) {
            $user->update(['current_tenant_id' => null]);
        }
    }

    public function scopeToCurrentTenant($query)
    {
        $tenant = $this->getCurrentTenant();
        
        if ($tenant) {
            return $query->where('tenant_id', $tenant->id);
        }
        
        return $query;
    }

    public function ensureSlugIsUnique(string $slug): string
    {
        $originalSlug = $slug;
        $counter = 1;
        
        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
