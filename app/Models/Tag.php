<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'color',
        'sort_order',
        'tenant_id',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget(config('default.catalogue_cache_key'));
        });

        static::deleted(function () {
            Cache::forget(config('default.catalogue_cache_key'));
        });

        // Auto-assign tenant_id when creating
        static::creating(function ($model) {
            if (!$model->tenant_id && Auth::check() && Auth::user()->current_tenant_id) {
                $model->tenant_id = Auth::user()->current_tenant_id;
            }
        });
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class)
            ->using(ItemTag::class)
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }
}
