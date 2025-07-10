<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

class Item extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'sort_order',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)
            ->using(ItemTag::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function tagsOrdered(): BelongsToMany
    {
        return $this->tags()->orderBy('item_tag.sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

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
