<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ItemResource\Pages;
use App\Filament\Admin\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use App\Services\TenantService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tenant_id')
                    ->label('Restaurant')
                    ->relationship('tenant', 'name', function (Builder $query) {
                        // Non-admin users can only see their assigned tenants
                        if (!Auth::user()->is_admin) {
                            $userTenantIds = Auth::user()->tenants->pluck('id');
                            $query->whereIn('id', $userTenantIds);
                        }
                        return $query;
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('category_id', null);
                        $set('tags', []);
                    }),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name', function (Builder $query, Forms\Get $get) {
                        $tenantId = $get('tenant_id');
                        if ($tenantId) {
                            $query->where('tenant_id', $tenantId);
                        } else if (!Auth::user()->is_admin) {
                            // For non-admin users, scope to their tenants
                            $userTenantIds = Auth::user()->tenants->pluck('id');
                            $query->whereIn('tenant_id', $userTenantIds);
                        }
                        return $query;
                    })
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(1000)
                    ->rows(3),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01),
                Forms\Components\Select::make('tags')
                    ->relationship('tags', 'name', function (Builder $query, Forms\Get $get) {
                        $tenantId = $get('tenant_id');
                        if ($tenantId) {
                            $query->where('tenant_id', $tenantId);
                        } else if (!Auth::user()->is_admin) {
                            // For non-admin users, scope to their tenants
                            $userTenantIds = Auth::user()->tenants->pluck('id');
                            $query->whereIn('tenant_id', $userTenantIds);
                        }
                        return $query;
                    })
                    ->multiple()
                    ->preload(),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                SpatieMediaLibraryFileUpload::make('photo')
                    ->label('Photo')
                    ->collection('photo')
                    ->acceptedFileTypes(['image/*'])
                    ->maxFiles(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->label('Photo')
                    ->collection('photo')
                    ->conversion('thumb')
                    ->height(40)
                    ->width(40)
                    ->circular(),
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Restaurant')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tags.name')
                    ->badge()
                    ->separator(',')
                    ->limit(3),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tenant_id')
                    ->label('Restaurant')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }

    // Admin users can see all data across all tenants, non-admin users see only their tenant data
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['tenant', 'category']);
        
        // If user is not an admin, scope to their tenants
        if (!Auth::user()->is_admin) {
            $userTenantIds = Auth::user()->tenants->pluck('id');
            $query->whereIn('tenant_id', $userTenantIds);
        }
        
        return $query;
    }
}
