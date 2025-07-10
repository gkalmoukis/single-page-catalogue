<?php

namespace App\Filament\Tenant\Resources\ItemResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Services\TenantService;
use Illuminate\Support\Facades\Auth;

class TagsRelationManager extends RelationManager
{
    protected static string $relationship = 'tags';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\ColorPicker::make('color')
                    ->required()
                    ->default('#6B7280'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label('Color'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('sort_order')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make()
                    ->form(fn (Forms\Form $form): Forms\Form => $form
                        ->schema([
                            Forms\Components\Select::make('recordId')
                                ->label('Tag')
                                ->options(function () {
                                    // Get current tenant using TenantService
                                    $tenantService = app(TenantService::class);
                                    $currentTenant = $tenantService->getCurrentTenant();
                                    
                                    if ($currentTenant) {
                                        return \App\Models\Tag::where('tenant_id', $currentTenant->id)
                                            ->pluck('name', 'id');
                                    }
                                    
                                    // Fallback: get tags from user's assigned tenants
                                    $userTenantIds = Auth::user()->tenants->pluck('id');
                                    return \App\Models\Tag::whereIn('tenant_id', $userTenantIds)
                                        ->pluck('name', 'id');
                                })
                                ->required()
                                ->searchable(),
                        ])
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(fn (Forms\Form $form): Forms\Form => $form
                        ->schema([
                        ])
                    ),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
