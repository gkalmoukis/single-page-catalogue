<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\CategoryResource\Pages;
use App\Filament\Tenant\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Services\TenantService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Catalog Management';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['tenant']);
        
        // Use TenantService to scope to current tenant
        $tenantService = app(TenantService::class);
        $currentTenant = $tenantService->getCurrentTenant();
        
        if ($currentTenant) {
            return $query->where('tenant_id', $currentTenant->id);
        }
        
        // Fallback: Tenant users can only see data from their assigned tenants
        $userTenantIds = Auth::user()->tenants->pluck('id');
        return $query->whereIn('tenant_id', $userTenantIds);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tenant_id')
                    ->label('Restaurant')
                    ->relationship('tenant', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(function () {
                        $tenantService = app(TenantService::class);
                        $currentTenant = $tenantService->getCurrentTenant();
                        return $currentTenant?->id ?? Auth::user()->tenants->first()?->id;
                    })
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Brief description of this category...'),
                Forms\Components\TextInput::make('emoji')
                    ->maxLength(255)
                    ->placeholder('🍕'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('emoji')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
