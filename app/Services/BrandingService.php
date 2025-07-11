<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;

class BrandingService
{
    /**
     * Get branding information for a tenant
     */
    public function getBrandingForTenant(Tenant $tenant): array
    {
        return Cache::remember("tenant_branding_{$tenant->id}", 3600, function () use ($tenant) {
            return [
                'logo_url' => $tenant->logo_url,
                'logo_thumb_url' => $tenant->logo_thumb_url,
                'logo_medium_url' => $tenant->getLogoUrl('medium'),
                'logo_large_url' => $tenant->getLogoUrl('large'),
                'has_logo' => $tenant->hasLogo(),
                'primary_color' => $tenant->primary_color ?? '#3B82F6',
                'secondary_color' => $tenant->secondary_color ?? '#6B7280',
                'business_description' => $tenant->business_description,
                'contact' => [
                    'phone' => $tenant->phone,
                    'email' => $tenant->email,
                    'address' => $tenant->address,
                ],
                'timetable' => $tenant->formatted_timetable,
                'social_links' => $tenant->formatted_social_links,
            ];
        });
    }

    /**
     * Get CSS variables for tenant branding colors
     */
    public function getCssVariables(Tenant $tenant): string
    {
        $branding = $this->getBrandingForTenant($tenant);
        
        return sprintf(
            ':root { --primary-color: %s; --secondary-color: %s; }',
            $branding['primary_color'],
            $branding['secondary_color']
        );
    }

    /**
     * Check if tenant has complete branding setup
     */
    public function hasCompleteBranding(Tenant $tenant): bool
    {
        return $tenant->hasLogo() && 
               !empty($tenant->business_description) &&
               !empty($tenant->primary_color);
    }

    /**
     * Get WhatsApp link for tenant
     */
    public function getWhatsAppLink(Tenant $tenant, string $message = null): ?string
    {
        $whatsapp = $tenant->formatted_social_links['whatsapp'] ?? null;
        
        if (!$whatsapp) {
            return null;
        }

        $phone = preg_replace('/[^0-9+]/', '', $whatsapp);
        $encodedMessage = $message ? urlencode($message) : '';
        
        return "https://wa.me/{$phone}" . ($encodedMessage ? "?text={$encodedMessage}" : '');
    }
}