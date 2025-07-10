<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'System Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
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
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                Forms\Components\Toggle::make('is_admin')
                    ->label('Admin User')
                    ->helperText('Admin users can access the admin dashboard and manage all tenants'),
                Forms\Components\Select::make('tenants')
                    ->relationship('tenants', 'name', function (Builder $query) {
                        // Non-admin users can only see their assigned tenants
                        if (!Auth::user()->is_admin) {
                            $userTenantIds = Auth::user()->tenants->pluck('id');
                            $query->whereIn('id', $userTenantIds);
                        }
                        return $query;
                    })
                    ->multiple()
                    ->preload()
                    ->hidden(fn (Forms\Get $get) => $get('is_admin'))
                    ->helperText('Select which tenants this user can access'),
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
                Tables\Columns\TextColumn::make('tenants.name')
                    ->badge()
                    ->separator(',')
                    ->limit(3)
                    ->label('Tenants'),
                Tables\Columns\IconColumn::make('is_admin')
                    ->boolean()
                    ->label('Admin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_admin')
                    ->label('Admin Users')
                    ->boolean()
                    ->trueLabel('Admin users only')
                    ->falseLabel('Non-admin users only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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

    // Admin users can see all users, non-admin users can only see users from their tenants
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['tenants']);
        
        // If user is not an admin, scope to users who belong to the same tenants
        if (!Auth::user()->is_admin) {
            $userTenantIds = Auth::user()->tenants->pluck('id');
            $query->whereHas('tenants', function (Builder $query) use ($userTenantIds) {
                $query->whereIn('tenant_id', $userTenantIds);
            });
        }
        
        return $query;
    }
}
