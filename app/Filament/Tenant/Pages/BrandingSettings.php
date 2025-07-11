<?php

namespace App\Filament\Tenant\Pages;

use App\Models\Tenant;
use App\Services\TenantService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class BrandingSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static string $view = 'filament.tenant.pages.branding-settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Branding & Design';

    protected static ?string $title = 'Branding & Design Settings';

    protected static ?int $navigationSort = 10;

    public ?array $data = [];

    public function mount(): void
    {
        $tenant = app(TenantService::class)->getCurrentTenant();
        
        if (!$tenant) {
            abort(404, 'Tenant not found');
        }

        $this->data = [
            'business_description' => $tenant->business_description,
            'phone' => $tenant->phone,
            'email' => $tenant->email,
            'address' => $tenant->address,
            'timetable' => $tenant->formatted_timetable,
            'social_links' => $tenant->formatted_social_links,
            'primary_color' => $tenant->primary_color ?? '#3B82F6',
            'secondary_color' => $tenant->secondary_color ?? '#6B7280',
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Logo & Brand Colors')
                    ->description('Upload your logo and set your brand colors')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label('Restaurant Logo')
                            ->collection('logo')
                            ->image()
                            ->imageResizeMode('contain')
                            ->imageCropAspectRatio('16:9')
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
                                    ->helperText('Main brand color for buttons and accents'),
                                Forms\Components\ColorPicker::make('secondary_color')
                                    ->label('Secondary Brand Color')
                                    ->helperText('Secondary color for text and borders'),
                            ]),
                    ]),

                Forms\Components\Section::make('Business Information')
                    ->description('Tell customers about your restaurant')
                    ->schema([
                        Forms\Components\Textarea::make('business_description')
                            ->label('Business Description')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Tell customers about your restaurant, cuisine, and what makes you special...')
                            ->columnSpanFull(),
                        
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
                            ->placeholder('Full business address including street, city, postal code')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Opening Hours')
                    ->description('Set your weekly operating schedule')
                    ->schema([
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\KeyValue::make('timetable')
                                    ->label('Weekly Schedule')
                                    ->keyLabel('Day')
                                    ->valueLabel('Hours / Status')
                                    ->default([
                                        'Monday' => '09:00 - 22:00',
                                        'Tuesday' => '09:00 - 22:00',
                                        'Wednesday' => '09:00 - 22:00',
                                        'Thursday' => '09:00 - 22:00',
                                        'Friday' => '09:00 - 22:00',
                                        'Saturday' => '09:00 - 22:00',
                                        'Sunday' => '09:00 - 22:00',
                                    ])
                                    ->helperText('Enter hours like "09:00 - 22:00" or "Closed" for closed days'),
                            ]),
                    ]),

                Forms\Components\Section::make('Social Media & Online Presence')
                    ->description('Connect with customers on social media')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('social_links.website')
                                    ->label('Website URL')
                                    ->url()
                                    ->placeholder('https://www.yourrestaurant.com')
                                    ->prefixIcon('heroicon-m-globe-alt'),
                                Forms\Components\TextInput::make('social_links.facebook')
                                    ->label('Facebook Page')
                                    ->url()
                                    ->placeholder('https://facebook.com/yourrestaurant')
                                    ->prefixIcon('heroicon-s-hashtag'),
                                Forms\Components\TextInput::make('social_links.instagram')
                                    ->label('Instagram Profile')
                                    ->url()
                                    ->placeholder('https://instagram.com/yourrestaurant')
                                    ->prefixIcon('heroicon-s-camera'),
                                Forms\Components\TextInput::make('social_links.twitter')
                                    ->label('Twitter/X Profile')
                                    ->url()
                                    ->placeholder('https://twitter.com/yourrestaurant')
                                    ->prefixIcon('heroicon-s-hashtag'),
                                Forms\Components\TextInput::make('social_links.whatsapp')
                                    ->label('WhatsApp Number')
                                    ->tel()
                                    ->placeholder('+30 210 123 4567')
                                    ->helperText('Include country code for WhatsApp links')
                                    ->prefixIcon('heroicon-s-phone'),
                            ]),
                    ]),
            ])
            ->statePath('data')
            ->model(app(TenantService::class)->getCurrentTenant());
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save Branding Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        $tenant = app(TenantService::class)->getCurrentTenant();
        
        if (!$tenant) {
            Notification::make()
                ->title('Error')
                ->body('Tenant not found')
                ->danger()
                ->send();
            return;
        }

        // Process timetable data
        $processedTimetable = [];
        if (isset($data['timetable']) && is_array($data['timetable'])) {
            foreach ($data['timetable'] as $day => $hours) {
                $processedTimetable[strtolower($day)] = [
                    'hours' => $hours,
                    'closed' => strtolower(trim($hours)) === 'closed'
                ];
            }
        }

        $tenant->update([
            'business_description' => $data['business_description'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => $data['address'] ?? null,
            'timetable' => $processedTimetable,
            'social_links' => $data['social_links'] ?? [],
            'primary_color' => $data['primary_color'] ?? '#3B82F6',
            'secondary_color' => $data['secondary_color'] ?? '#6B7280',
        ]);

        Notification::make()
            ->title('Settings Saved')
            ->body('Your branding settings have been updated successfully!')
            ->success()
            ->send();
    }
}