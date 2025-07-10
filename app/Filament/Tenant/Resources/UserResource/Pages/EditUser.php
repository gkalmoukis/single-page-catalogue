<?php

namespace App\Filament\Tenant\Resources\UserResource\Pages;

use App\Filament\Tenant\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Remove Access')
                ->modalHeading('Remove User Access')
                ->modalDescription('Are you sure you want to remove this user\'s access to this tenant? This will not delete the user account, only their access to this tenant.')
                ->successNotificationTitle('User access removed')
                ->visible(fn () => !$this->record->is_admin)
                ->before(function () {
                    $tenantService = app(\App\Services\TenantService::class);
                    $currentTenant = $tenantService->getCurrentTenant();
                    
                    if ($currentTenant) {
                        $tenantService->removeUserFromTenant($currentTenant, $this->record);
                    }
                })
                ->after(function () {
                    return redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}