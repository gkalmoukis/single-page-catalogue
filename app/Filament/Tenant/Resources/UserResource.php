<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\UserResource\Pages;
use App\Filament\Tenant\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Services\TenantService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Team Members';

    protected static ?string $modelLabel = 'Team Member';

    protected static ?string $pluralModelLabel = 'Team Members';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['tenants']);
        
        // Use TenantService to scope to current tenant users
        $tenantService = app(TenantService::class);
        $currentTenant = $tenantService->getCurrentTenant();
        
        if ($currentTenant) {
            // Only show users who belong to the current tenant
            return $query->whereHas('tenants', function (Builder $query) use ($currentTenant) {
                $query->where('tenant_id', $currentTenant->id);
            });
        }
        
        // Fallback: Show users from current user's tenants
        $userTenantIds = Auth::user()->tenants->pluck('id');
        return $query->whereHas('tenants', function (Builder $query) use ($userTenantIds) {
            $query->whereIn('tenant_id', $userTenantIds);
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn ($context) => $context === 'create')
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                            ->helperText('Leave empty to keep current password when editing'),
                    ]),
                Forms\Components\Section::make('Tenant Access')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'manager' => 'Manager',
                                'editor' => 'Editor',
                                'viewer' => 'Viewer',
                            ])
                            ->default('editor')
                            ->required()
                            ->helperText('Role determines the user\'s permissions within this tenant'),
                        Forms\Components\Toggle::make('is_admin')
                            ->label('System Admin')
                            ->helperText('System admins can access all tenants and the main admin dashboard')
                            ->disabled()
                            ->dehydrated(false)
                            ->visible(fn () => Auth::user()->is_admin),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pivot.role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'owner' => 'success',
                        'admin' => 'warning',
                        'manager' => 'info',
                        'editor' => 'gray',
                        'viewer' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('System Admin')
                    ->boolean()
                    ->trueColor('danger')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'owner' => 'Owner',
                        'admin' => 'Admin',
                        'manager' => 'Manager',
                        'editor' => 'Editor',
                        'viewer' => 'Viewer',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!$data['value']) {
                            return $query;
                        }
                        
                        return $query->whereHas('tenants', function (Builder $query) use ($data) {
                            $query->where('role', $data['value']);
                        });
                    }),
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('System Admin')
                    ->placeholder('All users')
                    ->trueLabel('System admins only')
                    ->falseLabel('Regular users only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data, User $record): array {
                        $tenantService = app(TenantService::class);
                        $currentTenant = $tenantService->getCurrentTenant();
                        
                        if ($currentTenant) {
                            $pivot = $record->tenants()->where('tenant_id', $currentTenant->id)->first();
                            $data['role'] = $pivot?->pivot->role ?? 'editor';
                        }
                        
                        return $data;
                    })
                    ->using(function (array $data, User $record): User {
                        $tenantService = app(TenantService::class);
                        $currentTenant = $tenantService->getCurrentTenant();
                        
                        // Update user data
                        $record->update([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => isset($data['password']) && $data['password'] ? bcrypt($data['password']) : $record->password,
                        ]);
                        
                        // Update role in tenant pivot
                        if ($currentTenant && isset($data['role'])) {
                            $record->tenants()->updateExistingPivot($currentTenant->id, ['role' => $data['role']]);
                        }
                        
                        return $record;
                    }),
                Tables\Actions\Action::make('remove_from_tenant')
                    ->label('Remove Access')
                    ->icon('heroicon-o-user-minus')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Remove User Access')
                    ->modalDescription('Are you sure you want to remove this user\'s access to this tenant? This action cannot be undone.')
                    ->visible(fn (User $record) => !$record->is_admin && $record->tenants()->where('tenant_id', app(TenantService::class)->getCurrentTenant()?->id)->exists())
                    ->action(function (User $record) {
                        $tenantService = app(TenantService::class);
                        $currentTenant = $tenantService->getCurrentTenant();
                        
                        if ($currentTenant) {
                            $tenantService->removeUserFromTenant($currentTenant, $record);
                            
                            Notification::make()
                                ->title('User access removed')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('change_role')
                        ->label('Change Role')
                        ->icon('heroicon-o-user-circle')
                        ->form([
                            Forms\Components\Select::make('role')
                                ->label('New Role')
                                ->options([
                                    'admin' => 'Admin',
                                    'manager' => 'Manager',
                                    'editor' => 'Editor',
                                    'viewer' => 'Viewer',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            $tenantService = app(TenantService::class);
                            $currentTenant = $tenantService->getCurrentTenant();
                            
                            if ($currentTenant) {
                                foreach ($records as $record) {
                                    if (!$record->is_admin) {
                                        $record->tenants()->updateExistingPivot($currentTenant->id, ['role' => $data['role']]);
                                    }
                                }
                                
                                Notification::make()
                                    ->title('Roles updated successfully')
                                    ->success()
                                    ->send();
                            }
                        }),
                    Tables\Actions\BulkAction::make('remove_access')
                        ->label('Remove Access')
                        ->icon('heroicon-o-user-minus')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $tenantService = app(TenantService::class);
                            $currentTenant = $tenantService->getCurrentTenant();
                            
                            if ($currentTenant) {
                                foreach ($records as $record) {
                                    if (!$record->is_admin) {
                                        $tenantService->removeUserFromTenant($currentTenant, $record);
                                    }
                                }
                                
                                Notification::make()
                                    ->title('User access removed')
                                    ->success()
                                    ->send();
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}