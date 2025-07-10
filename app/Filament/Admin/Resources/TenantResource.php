<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TenantResource\Pages;
use App\Filament\Admin\Resources\TenantResource\RelationManagers;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use SimpleSoftwareIO\QrCode\Generator;
use Filament\Support\Enums\ActionSize;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    
    protected static ?string $navigationGroup = 'System Management';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('URL-friendly identifier for the tenant'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000)
                            ->rows(3),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Whether this tenant is active and can be accessed'),
                    ]),
                
                Forms\Components\Section::make('Homepage & QR Code')
                    ->schema([
                        Forms\Components\Placeholder::make('homepage_url')
                            ->label('Homepage URL')
                            ->content(function ($record) {
                                if (!$record || !$record->slug) {
                                    return 'Save the tenant first to generate homepage URL';
                                }
                                $url = url("/t/{$record->slug}");
                                return new \Illuminate\Support\HtmlString(
                                    '<div class="space-y-2">' .
                                    '<div class="font-mono text-sm bg-gray-100 dark:bg-gray-800 p-2 rounded">' . $url . '</div>' .
                                    '<div class="flex space-x-2">' .
                                    '<a href="' . $url . '" target="_blank" class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800">' .
                                    '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd"></path><path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd"></path></svg>' .
                                    'Visit Homepage' .
                                    '</a>' .
                                    '</div>' .
                                    '</div>'
                                );
                            })
                            ->visible(fn ($record) => $record && $record->slug),
                        
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('generate_qr_code')
                                ->label('Generate QR Code')
                                ->icon('heroicon-o-qr-code')
                                ->color('success')
                                ->size(ActionSize::Medium)
                                ->modalHeading(fn ($record) => "QR Code for {$record->name}")
                                ->modalContent(function ($record) {
                                    if (!$record || !$record->slug) {
                                        return 'Please save the tenant first to generate QR code';
                                    }
                                    
                                    $url = url("/t/{$record->slug}");
                                    $qrCode = app(Generator::class)->format('svg')
                                        ->size(250)
                                        ->errorCorrection('M')
                                        ->generate($url);
                                    
                                    return new \Illuminate\Support\HtmlString(
                                        '<div class="text-center space-y-4">' .
                                        '<div class="flex justify-center">' . $qrCode . '</div>' .
                                        '<div class="text-sm text-gray-600 dark:text-gray-400">' .
                                        '<p class="font-mono bg-gray-100 dark:bg-gray-800 p-2 rounded">' . $url . '</p>' .
                                        '<p class="mt-2">Scan this QR code to visit the homepage</p>' .
                                        '</div>' .
                                        '</div>'
                                    );
                                })
                                ->modalActions([
                                    Forms\Components\Actions\Action::make('download')
                                        ->label('Download PNG')
                                        ->icon('heroicon-o-arrow-down-tray')
                                        ->action(function ($record) {
                                            $url = url("/t/{$record->slug}");
                                            $qrCode = app(Generator::class)->format('png')
                                                ->size(300)
                                                ->errorCorrection('M')
                                                ->generate($url);
                                            
                                            $filename = "qr-code-{$record->slug}.png";
                                            
                                            return response($qrCode)
                                                ->header('Content-Type', 'image/png')
                                                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
                                        }),
                                    Forms\Components\Actions\Action::make('close')
                                        ->label('Close')
                                        ->color('gray')
                                        ->action(fn () => null),
                                ])
                                ->visible(fn ($record) => $record && $record->slug),
                        ])
                        ->visible(fn ($record) => $record && $record->slug),
                    ])
                    ->visible(fn ($record) => $record && $record->slug),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('homepage_url')
                    ->label('Homepage URL')
                    ->getStateUsing(fn ($record) => url("/t/{$record->slug}"))
                    ->url(fn ($record) => url("/t/{$record->slug}"))
                    ->openUrlInNewTab()
                    ->copyable()
                    ->tooltip('Click to visit or copy URL'),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories_count')
                    ->counts('categories')
                    ->label('Categories')
                    ->sortable(),
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
                //
            ])
            ->actions([
                Tables\Actions\Action::make('qr_code')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->modalHeading(fn ($record) => "QR Code for {$record->name}")
                    ->modalContent(function ($record) {
                        $url = url("/t/{$record->slug}");
                        $qrCode = app(Generator::class)->format('svg')
                            ->size(250)
                            ->errorCorrection('M')
                            ->generate($url);
                        
                        return new \Illuminate\Support\HtmlString(
                            '<div class="text-center space-y-4">' .
                            '<div class="flex justify-center">' . $qrCode . '</div>' .
                            '<div class="text-sm text-gray-600 dark:text-gray-400">' .
                            '<p class="font-mono bg-gray-100 dark:bg-gray-800 p-2 rounded">' . $url . '</p>' .
                            '<p class="mt-2">Scan this QR code to visit the homepage</p>' .
                            '</div>' .
                            '</div>'
                        );
                    })
                    ->modalActions([
                        Tables\Actions\Action::make('download')
                            ->label('Download PNG')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->action(function ($record) {
                                $url = url("/t/{$record->slug}");
                                $qrCode = app(Generator::class)->format('png')
                                    ->size(300)
                                    ->errorCorrection('M')
                                    ->generate($url);
                                
                                $filename = "qr-code-{$record->slug}.png";
                                
                                return response($qrCode)
                                    ->header('Content-Type', 'image/png')
                                    ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
                            }),
                        Tables\Actions\Action::make('close')
                            ->label('Close')
                            ->color('gray')
                            ->action(fn () => null),
                    ]),
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
