<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class TenantMediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        // Get the model
        $model = $media->model;
        
        if ($model instanceof \App\Models\Tenant) {
            return "tenants/{$model->id}/{$media->collection_name}/";
        }
        
        if ($model instanceof \App\Models\Item) {
            return "tenants/{$model->tenant_id}/items/{$model->id}/{$media->collection_name}/";
        }
        
        // Fallback to default behavior for other models
        return $media->id . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        // Get the model
        $model = $media->model;
        
        if ($model instanceof \App\Models\Tenant) {
            return "tenants/{$model->id}/{$media->collection_name}/conversions/";
        }
        
        if ($model instanceof \App\Models\Item) {
            return "tenants/{$model->tenant_id}/items/{$model->id}/{$media->collection_name}/conversions/";
        }
        
        // Fallback to default behavior for other models
        return $media->id . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        // Get the model
        $model = $media->model;
        
        if ($model instanceof \App\Models\Tenant) {
            return "tenants/{$model->id}/{$media->collection_name}/responsive-images/";
        }
        
        if ($model instanceof \App\Models\Item) {
            return "tenants/{$model->tenant_id}/items/{$model->id}/{$media->collection_name}/responsive-images/";
        }
        
        // Fallback to default behavior for other models
        return $media->id . '/responsive-images/';
    }
}