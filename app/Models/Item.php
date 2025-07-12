<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Item extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'price',
        'description',
        'sort_order',
        'category_id',
        'is_active',
        'tenant_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Define media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->singleFile()
            ->useDisk('public');
    }

    /**
     * Define media conversions
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10)
            ->optimize()
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->optimize()
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(800)
            ->height(600)
            ->sharpen(10)
            ->optimize()
            ->format('webp')
            ->nonQueued();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
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

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Check if item has a photo
     */
    public function hasPhoto(): bool
    {
        return $this->hasMedia('photo');
    }

    /**
     * Get the photo URL for a specific conversion
     */
    public function getPhotoUrl(string $conversion = ''): ?string
    {
        if (!$this->hasPhoto()) {
            return null;
        }

        $media = $this->getFirstMedia('photo');
        
        if ($conversion && $media->hasGeneratedConversion($conversion)) {
            return $media->getUrl($conversion);
        }
        
        return $media->getUrl();
    }

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
}
