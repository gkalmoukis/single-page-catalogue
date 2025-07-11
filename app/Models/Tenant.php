<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Tenant extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'database',
        'data',
        'is_active',
        'trial_ends_at',
        // Branding fields
        'business_description',
        'phone',
        'email',
        'address',
        'timetable',
        'social_links',
        'primary_color',
        'secondary_color',
    ];

    protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
        'trial_ends_at' => 'datetime',
        'timetable' => 'array',
        'social_links' => 'array',
    ];

    /**
     * Define media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
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
            ->format('webp') // Use WebP for better compression
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(400)
            ->height(225)
            ->sharpen(10)
            ->optimize()
            ->format('webp') // Use WebP for better compression
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(800)
            ->height(450)
            ->sharpen(10)
            ->optimize()
            ->format('webp') // Use WebP for better compression
            ->nonQueued();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    public function getFullDomainAttribute(): string
    {
        return config('app.url') . '/t/' . $this->slug;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the logo URL (using media library)
     */
    public function getLogoUrlAttribute(): ?string
    {
        $logo = $this->getFirstMedia('logo');
        return $logo ? $logo->getUrl() : null;
    }

    /**
     * Get logo URL for a specific conversion
     */
    public function getLogoUrl(string $conversion = ''): ?string
    {
        $logo = $this->getFirstMedia('logo');
        if (!$logo) {
            return null;
        }

        return $conversion ? $logo->getUrl($conversion) : $logo->getUrl();
    }

    /**
     * Get logo thumbnail URL
     */
    public function getLogoThumbUrlAttribute(): ?string
    {
        return $this->getLogoUrl('thumb');
    }

    /**
     * Check if tenant has a logo
     */
    public function hasLogo(): bool
    {
        return $this->getFirstMedia('logo') !== null;
    }

    /**
     * Get formatted timetable for display
     */
    public function getFormattedTimetableAttribute(): array
    {
        $defaultTimetable = [
            'monday' => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
            'tuesday' => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
            'wednesday' => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
            'thursday' => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
            'friday' => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
            'saturday' => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
            'sunday' => ['open' => '09:00', 'close' => '22:00', 'closed' => false],
        ];

        return array_merge($defaultTimetable, $this->timetable ?? []);
    }

    /**
     * Get formatted social links for display
     */
    public function getFormattedSocialLinksAttribute(): array
    {
        $defaultSocial = [
            'facebook' => '',
            'instagram' => '',
            'twitter' => '',
            'website' => '',
            'whatsapp' => '',
        ];

        return array_merge($defaultSocial, $this->social_links ?? []);
    }
}
