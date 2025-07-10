<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Cache;

class ItemTag extends Pivot
{
    protected $table = 'item_tag';

    protected $fillable = [
        'item_id',
        'tag_id',
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
}