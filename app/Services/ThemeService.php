<?php

namespace App\Services;

class ThemeService
{
    /**
     * Get all available themes
     */
    public function getAvailableThemes(): array
    {
        return [
            'classic' => [
                'name' => 'Classic',
                'description' => 'Clean and traditional layout with elegant typography',
                'preview' => '/images/themes/classic-preview.jpg',
                'view' => 'restaurant.themes.classic',
            ],
            'modern' => [
                'name' => 'Modern',
                'description' => 'Contemporary design with bold colors and modern aesthetics',
                'preview' => '/images/themes/modern-preview.jpg',
                'view' => 'restaurant.themes.modern',
            ],
            'elegant' => [
                'name' => 'Elegant',
                'description' => 'Sophisticated layout with refined styling and premium feel',
                'preview' => '/images/themes/elegant-preview.jpg',
                'view' => 'restaurant.themes.elegant',
            ],
            'minimal' => [
                'name' => 'Minimal',
                'description' => 'Minimalist design focusing on content with clean lines',
                'preview' => '/images/themes/minimal-preview.jpg',
                'view' => 'restaurant.themes.minimal',
            ],
        ];
    }

    /**
     * Get theme configuration by key
     */
    public function getTheme(string $themeKey): ?array
    {
        $themes = $this->getAvailableThemes();
        return $themes[$themeKey] ?? null;
    }

    /**
     * Get theme view name for rendering
     */
    public function getThemeView(string $themeKey): string
    {
        $theme = $this->getTheme($themeKey);
        return $theme['view'] ?? 'restaurant.themes.classic';
    }

    /**
     * Get theme options for form select
     */
    public function getThemeOptions(): array
    {
        $themes = $this->getAvailableThemes();
        $options = [];
        
        foreach ($themes as $key => $theme) {
            $options[$key] = $theme['name'] . ' - ' . $theme['description'];
        }
        
        return $options;
    }

    /**
     * Validate if theme exists
     */
    public function isValidTheme(string $themeKey): bool
    {
        return array_key_exists($themeKey, $this->getAvailableThemes());
    }
}