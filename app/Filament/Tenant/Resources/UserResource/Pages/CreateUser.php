<?php

namespace App\Filament\Tenant\Resources\UserResource\Pages;

use App\Filament\Tenant\Resources\UserResource;
use App\Services\TenantService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    
    protected string $roleData = 'editor';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove role from user data as it will be handled in the pivot table
        $this->roleData = $data['role'] ?? 'editor';
        unset($data['role']);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $tenantService = app(TenantService::class);
        $currentTenant = $tenantService->getCurrentTenant();
        
        if ($currentTenant) {
            // Add the new user to the current tenant with the specified role
            $tenantService->addUserToTenant($currentTenant, $this->record, $this->roleData);
            
            Notification::make()
                ->title('Team member invited successfully')
                ->body('The new user has been created and given access to this tenant')
                ->success()
                ->send();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}