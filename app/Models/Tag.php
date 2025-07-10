<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'color',
        'sort_order',
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget(config('default.catalogue_cache_key'));
        });

        static::deleted(function () {
            Cache::forget(config('default.catalogue_cache_key'));
        });
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class)
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }
}
