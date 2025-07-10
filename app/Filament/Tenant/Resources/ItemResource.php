<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\ItemResource\Pages;
use App\Filament\Tenant\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use App\Services\TenantService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Catalog Management';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['tenant', 'category']);
        
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
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name', function (Builder $query) {
                        $tenantService = app(TenantService::class);
                        $currentTenant = $tenantService->getCurrentTenant();
                        
                        if ($currentTenant) {
                            $query->where('tenant_id', $currentTenant->id);
                        } else {
                            $userTenantIds = Auth::user()->tenants->pluck('id');
                            $query->whereIn('tenant_id', $userTenantIds);
                        }
                        return $query;
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('tags')
                    ->relationship('tags', 'name', function (Builder $query) {
                        $tenantService = app(TenantService::class);
                        $currentTenant = $tenantService->getCurrentTenant();
                        
                        if ($currentTenant) {
                            $query->where('tenant_id', $currentTenant->id);
                        } else {
                            $userTenantIds = Auth::user()->tenants->pluck('id');
                            $query->whereIn('tenant_id', $userTenantIds);
                        }
                        return $query;
                    })
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('€')
                    ->step(0.01),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name', function (Builder $query) {
                        $tenantService = app(TenantService::class);
                        $currentTenant = $tenantService->getCurrentTenant();
                        
                        if ($currentTenant) {
                            $query->where('tenant_id', $currentTenant->id);
                        } else {
                            $userTenantIds = Auth::user()->tenants->pluck('id');
                            $query->whereIn('tenant_id', $userTenantIds);
                        }
                        return $query;
                    }),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All items')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\TagsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'view' => Pages\ViewItem::route('/{record}'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
