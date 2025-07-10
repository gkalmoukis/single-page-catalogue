<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TagResource\Pages;
use App\Filament\Admin\Resources\TagResource\RelationManagers;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 3;

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
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->helperText('Color for display purposes'),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tenant.name')
                    ->label('Restaurant')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items')
                    ->sortable(),
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
                    ->relationship('tenant', 'name', function (Builder $query) {
                        if (!Auth::user()->is_admin) {
                            $userTenantIds = Auth::user()->tenants->pluck('id');
                            $query->whereIn('id', $userTenantIds);
                        }
                        return $query;
                    })
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }

    // Admin users can see all data across all tenants, non-admin users see only their tenant data
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['tenant']);
        
        // If user is not an admin, scope to their tenants
        if (!Auth::user()->is_admin) {
            $userTenantIds = Auth::user()->tenants->pluck('id');
            $query->whereIn('tenant_id', $userTenantIds);
        }
        
        return $query;
    }
}
