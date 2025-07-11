<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\UrlGenerator\DefaultUrlGenerator;

class TenantMediaUrlGenerator extends DefaultUrlGenerator
{
    public function getUrl(): string
    {
        $model = $this->media->model;
        
        if ($model instanceof \App\Models\Tenant) {
            $path = $this->getMediaPath();
            return config('app.url') . '/storage/' . $path;
        }
        
        // Fallback to default behavior for other models
        return parent::getUrl();
    }
    
    protected function getMediaPath(): string
    {
        $model = $this->media->model;
        $basePath = "tenants/{$model->id}/{$this->media->collection_name}";
        
        if ($this->conversion) {
            // For conversions (thumbnails, etc.)
            // Conversions use WebP format regardless of original format
            $pathInfo = pathinfo($this->media->file_name);
            $nameWithoutExtension = $pathInfo['filename'];
            $conversionFileName = "{$nameWithoutExtension}-{$this->conversion->getName()}.webp";
            
            return "{$basePath}/conversions/{$conversionFileName}";
        }
        
        // For original files
        return "{$basePath}/{$this->media->file_name}";
    }
    
    public function getTemporaryUrl(\DateTimeInterface $expiration, array $options = []): string
    {
        // For temporary URLs, use the same logic as getUrl()
        return $this->getUrl();
    }
}