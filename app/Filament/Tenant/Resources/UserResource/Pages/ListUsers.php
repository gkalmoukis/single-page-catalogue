<?php

namespace App\Filament\Tenant\Resources\UserResource\Pages;

use App\Filament\Tenant\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Invite Team Member'),
            Actions\Action::make('invite_existing_user')
                ->label('Add Existing User')
                ->icon('heroicon-o-user-plus')
                ->color('gray')
                ->form([
                    \Filament\Forms\Components\TextInput::make('email')
                        ->label('User Email')
                        ->email()
                        ->required()
                        ->helperText('Enter the email of an existing user to give them access to this tenant'),
                    \Filament\Forms\Components\Select::make('role')
                        ->label('Role')
                        ->options([
                            'admin' => 'Admin',
                            'manager' => 'Manager',
                            'editor' => 'Editor',
                            'viewer' => 'Viewer',
                        ])
                        ->default('editor')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $tenantService = app(\App\Services\TenantService::class);
                    $currentTenant = $tenantService->getCurrentTenant();
                    
                    if (!$currentTenant) {
                        \Filament\Notifications\Notification::make()
                            ->title('Error: No tenant context')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    $user = \App\Models\User::where('email', $data['email'])->first();
                    
                    if (!$user) {
                        \Filament\Notifications\Notification::make()
                            ->title('User not found')
                            ->body('No user found with email: ' . $data['email'])
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    // Check if user is already part of this tenant
                    if ($user->tenants()->where('tenant_id', $currentTenant->id)->exists()) {
                        \Filament\Notifications\Notification::make()
                            ->title('User already has access')
                            ->body('This user already has access to this tenant')
                            ->warning()
                            ->send();
                        return;
                    }
                    
                    // Add user to tenant
                    $tenantService->addUserToTenant($currentTenant, $user, $data['role']);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('User added successfully')
                        ->body($user->name . ' now has ' . $data['role'] . ' access to this tenant')
                        ->success()
                        ->send();
                }),
        ];
    }
}