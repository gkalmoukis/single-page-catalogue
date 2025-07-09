<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Item extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'sort_order',
        'category_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
