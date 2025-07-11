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
use Filament\Support\Enums\ActionSize;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;

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

                Forms\Components\Section::make('Branding & Logo')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('logo')
                            ->image()
                            ->imageResizeMode('force')
                            ->imageResizeTargetWidth('400')
                            ->imageResizeTargetHeight('225')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->helperText('Upload your restaurant logo (max 2MB, recommended size: 400x225px)')
                            ->columnSpanFull(),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\ColorPicker::make('primary_color')
                                    ->label('Primary Brand Color')
                                    ->default('#3B82F6')
                                    ->helperText('Main brand color for buttons and accents'),
                                Forms\Components\ColorPicker::make('secondary_color')
                                    ->label('Secondary Brand Color')
                                    ->default('#6B7280')
                                    ->helperText('Secondary color for text and borders'),
                            ]),
                    ]),

                Forms\Components\Section::make('Business Details')
                    ->schema([
                        Forms\Components\Textarea::make('business_description')
                            ->label('Business Description')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Tell customers about your restaurant, cuisine, and what makes you special...'),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel()
                                    ->maxLength(255)
                                    ->placeholder('+30 210 123 4567'),
                                Forms\Components\TextInput::make('email')
                                    ->label('Business Email')
                                    ->email()
                                    ->maxLength(255)
                                    ->placeholder('info@restaurant.com'),
                            ]),
                        
                        Forms\Components\Textarea::make('address')
                            ->label('Address')
                            ->rows(2)
                            ->maxLength(500)
                            ->placeholder('Full business address including street, city, postal code'),
                    ]),

                Forms\Components\Section::make('Opening Hours')
                    ->schema([
                        Forms\Components\Repeater::make('timetable')
                            ->label('Weekly Schedule')
                            ->schema([
                                Forms\Components\Select::make('day')
                                    ->label('Day')
                                    ->options([
                                        'monday' => 'Monday',
                                        'tuesday' => 'Tuesday',
                                        'wednesday' => 'Wednesday',
                                        'thursday' => 'Thursday',
                                        'friday' => 'Friday',
                                        'saturday' => 'Saturday',
                                        'sunday' => 'Sunday',
                                    ])
                                    ->required()
                                    ->distinct(),
                                Forms\Components\Toggle::make('closed')
                                    ->label('Closed')
                                    ->default(false)
                                    ->live(),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TimePicker::make('open')
                                            ->label('Opening Time')
                                            ->default('09:00')
                                            ->hidden(fn (Forms\Get $get) => $get('closed')),
                                        Forms\Components\TimePicker::make('close')
                                            ->label('Closing Time')
                                            ->default('22:00')
                                            ->hidden(fn (Forms\Get $get) => $get('closed')),
                                    ]),
                            ])
                            ->default(function () {
                                return [
                                    ['day' => 'monday', 'open' => '09:00', 'close' => '22:00', 'closed' => false],
                                    ['day' => 'tuesday', 'open' => '09:00', 'close' => '22:00', 'closed' => false],
                                    ['day' => 'wednesday', 'open' => '09:00', 'close' => '22:00', 'closed' => false],
                                    ['day' => 'thursday', 'open' => '09:00', 'close' => '22:00', 'closed' => false],
                                    ['day' => 'friday', 'open' => '09:00', 'close' => '22:00', 'closed' => false],
                                    ['day' => 'saturday', 'open' => '09:00', 'close' => '22:00', 'closed' => false],
                                    ['day' => 'sunday', 'open' => '09:00', 'close' => '22:00', 'closed' => false],
                                ];
                            })
                            ->reorderable(false)
                            ->addActionLabel('Add Day')
                            ->maxItems(7)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Social Media & Online Presence')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('social_links.website')
                                    ->label('Website URL')
                                    ->url()
                                    ->placeholder('https://www.yourrestaurant.com'),
                                Forms\Components\TextInput::make('social_links.facebook')
                                    ->label('Facebook Page')
                                    ->url()
                                    ->placeholder('https://facebook.com/yourrestaurant'),
                                Forms\Components\TextInput::make('social_links.instagram')
                                    ->label('Instagram Profile')
                                    ->url()
                                    ->placeholder('https://instagram.com/yourrestaurant'),
                                Forms\Components\TextInput::make('social_links.twitter')
                                    ->label('Twitter/X Profile')
                                    ->url()
                                    ->placeholder('https://twitter.com/yourrestaurant'),
                                Forms\Components\TextInput::make('social_links.whatsapp')
                                    ->label('WhatsApp Number')
                                    ->tel()
                                    ->placeholder('+30 210 123 4567')
                                    ->helperText('Include country code for WhatsApp links'),
                            ]),
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
                                    $qrCode = app(\SimpleSoftwareIO\QrCode\Generator::class)->format('svg')
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
                                            $qrCode = app(\SimpleSoftwareIO\QrCode\Generator::class)->format('png')
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')
                    ->collection('logo')
                    ->conversion('thumb')
                    ->height(40)
                    ->width(60)
                    ->defaultImageUrl(url('/images/default-restaurant-logo.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->business_description ? \Illuminate\Support\Str::limit($record->business_description, 50) : null),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All tenants')
                    ->trueLabel('Active tenants')
                    ->falseLabel('Inactive tenants'),
                Tables\Filters\Filter::make('has_logo')
                    ->label('Has Logo')
                    ->query(fn (Builder $query): Builder => $query->whereHas('media', function ($query) {
                        $query->where('collection_name', 'logo');
                    })),
                Tables\Filters\Filter::make('has_social_links')
                    ->label('Has Social Links')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('social_links')),
            ])
            ->actions([
                Tables\Actions\Action::make('qr_code')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->modalHeading(fn ($record) => "QR Code for {$record->name}")
                    ->modalContent(function ($record) {
                        $url = url("/t/{$record->slug}");
                        $qrCode = app(\SimpleSoftwareIO\QrCode\Generator::class)->format('svg')
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
                                $qrCode = app(\SimpleSoftwareIO\QrCode\Generator::class)->format('png')
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
