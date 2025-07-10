<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'current_tenant_id',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)->withPivot('role')->withTimestamps();
    }

    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function hasAccessToTenant(Tenant $tenant): bool
    {
        return $this->tenants()->where('tenant_id', $tenant->id)->exists();
    }

    public function switchToTenant(Tenant $tenant): void
    {
        if ($this->hasAccessToTenant($tenant)) {
            $this->update(['current_tenant_id' => $tenant->id]);
        }
    }

    public function getTenants(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->tenants;
    }

    public function canAccessTenant(\Illuminate\Database\Eloquent\Model $tenant): bool
    {
        // Admin users can access any tenant
        if ($this->is_admin) {
            return true;
        }
        
        return $this->hasAccessToTenant($tenant);
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if user is a super admin (legacy method, now checks is_admin field)
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Get the tenants that the user can access (for Filament tenant selection)
     */
    public function getTenantOptions(): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->is_admin) {
            // Admin users can access all tenants
            return \App\Models\Tenant::all();
        }
        
        return $this->tenants;
    }
}
