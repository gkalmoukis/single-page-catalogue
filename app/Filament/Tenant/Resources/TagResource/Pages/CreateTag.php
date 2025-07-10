<?php

namespace App\Filament\Tenant\Resources\TagResource\Pages;

use App\Filament\Tenant\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;
}
