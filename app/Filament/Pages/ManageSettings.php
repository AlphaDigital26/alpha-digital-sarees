<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $title = 'Global Website Settings';
    protected static string $view = 'filament.pages.manage-settings';

    // This array holds the data for the form
    public ?array $data = [];

    // This controls whether the admin is allowed to edit or not
    public bool $isEditing = false;

    // When the page loads, fetch the first row of the database. If it doesn't exist, leave it blank.
    public function mount(): void
    {
        // Instantly fetches or creates the default row
        $setting = Setting::getSiteSettings();
        $this->form->fill($setting->toArray());
    }

    // Unlocks the form
    public function enableEditing(): void
    {
        $this->isEditing = true;
    }

    // Locks the form and resets to database values
    public function cancelEditing(): void
    {
        $this->isEditing = false;
        
        // Fetch the safe settings again
        $setting = Setting::getSiteSettings();
        $this->form->fill($setting->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Wrapping the entire form in a Group allows us to disable it all at once
                Forms\Components\Group::make()
                    ->disabled(fn () => ! $this->isEditing) // Locks fields if not editing
                    ->schema([
                        Forms\Components\Tabs::make('Settings')
                            ->tabs([
                                // LOGO TAB
                                Forms\Components\Tabs\Tab::make('Brand & Logo')
                                    ->icon('heroicon-o-sparkles')
                                    ->schema([
                                        Forms\Components\Radio::make('logo_type')
                                            ->label('Logo Format')
                                            ->options([
                                                'text' => 'Plain Text Logo',
                                                'image' => 'Image Upload Logo',
                                            ])
                                            ->default('text')
                                            ->inline()
                                            ->live(), // Instantly updates the form when clicked, filling the circle

                                        Forms\Components\TextInput::make('logo_text')
                                            ->label('Logo Text')
                                            ->placeholder('e.g., ALMAARI')
                                            ->visible(fn (Forms\Get $get) => $get('logo_type') === 'text'), // Only shows if 'text' is selected

                                        Forms\Components\FileUpload::make('logo_image')
                                            ->label('Upload Logo Image')
                                            ->image()
                                            ->directory('settings')
                                            ->visible(fn (Forms\Get $get) => $get('logo_type') === 'image'), // Only shows if 'image' is selected
                                    ]),

                                // CONTACT & SOCIAL TAB
                                Forms\Components\Tabs\Tab::make('Contact & Social')
                                    ->icon('heroicon-o-phone')
                                    ->schema([
                                        Forms\Components\TextInput::make('whatsapp_number')
                                            ->label('WhatsApp Number (For Orders)')
                                            ->prefix('+91')
                                            ->helperText('Enter the number where users will send their Saree orders. Include country code if not 91.'),
                                        
                                        Forms\Components\TextInput::make('contact_email')
                                            ->label('Support Email')
                                            ->email(),

                                        Forms\Components\TextInput::make('contact_phone')
                                            ->label('Support Phone'),

                                        Forms\Components\Textarea::make('contact_address')
                                            ->label('Showroom Address')
                                            ->columnSpanFull(),
                                            
                                        Forms\Components\TextInput::make('facebook_link')
                                            ->url()->label('Facebook URL'),
                                        Forms\Components\TextInput::make('instagram_link')
                                            ->url()->label('Instagram URL'),
                                    ])->columns(2),

                                // FOOTER TAB
                                Forms\Components\Tabs\Tab::make('Footer')
                                    ->icon('heroicon-o-document-text')
                                    ->schema([
                                        Forms\Components\Textarea::make('footer_text')
                                            ->label('Footer About Text')
                                            ->rows(4)
                                            ->columnSpanFull(),
                                    ]),
                            ])->columnSpanFull(),
                    ])->columnSpanFull()
            ])
            ->statePath('data'); // Connects the form schema to our $data array
    }

    // This runs when you click the Save button
    public function save(): void
    {
        // Find the first setting record, or create a brand new one if it's the first time
        $setting = Setting::first() ?? new Setting();
        
        // Fill it with the form data and save it
        $setting->fill($this->form->getState());
        $setting->save();

        // Lock the form back up
        $this->isEditing = false;

        // Show a little green success pop-up
        Notification::make()
            ->success()
            ->title('Settings Saved Successfully!')
            ->send();
    }
}