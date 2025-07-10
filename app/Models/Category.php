<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'emoji',
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

    public function items(): HasMany
    {
        return $this->hasMany(Item::class)->active()->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(Item::class)->orderBy('sort_order');
    }
}
