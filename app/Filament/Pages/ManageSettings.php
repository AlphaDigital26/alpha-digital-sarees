<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?int $navigationSort = 11;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Settings';
    protected static ?string $title = 'Global Website Settings';
    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];
    public bool $isEditing = false;

    public function mount(): void
    {
        $setting = Setting::getSiteSettings();
        $this->form->fill($setting->toArray());
    }

    public function enableEditing(): void
    {
        $this->isEditing = true;
    }

    public function cancelEditing(): void
    {
        $this->isEditing = false;
        $this->form->fill(Setting::getSiteSettings()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->disabled(fn () => ! $this->isEditing)
                    ->schema([
                        Forms\Components\Tabs::make('Settings')
                            ->tabs([
                                // TAB 1: Brand & Logo
                                Forms\Components\Tabs\Tab::make('Brand & Logo')
                                    ->icon('heroicon-o-sparkles')
                                    ->schema([
                                        Forms\Components\FileUpload::make('favicon_image')
                                            ->label('Favicon Image')
                                            ->image()
                                            ->directory('settings'),
                                        Forms\Components\Radio::make('logo_type')
                                            ->options(['text' => 'Plain Text', 'image' => 'Image Logo'])
                                            ->inline()->live(),
                                        Forms\Components\TextInput::make('logo_text')
                                            ->visible(fn ($get) => $get('logo_type') === 'text'),
                                        Forms\Components\FileUpload::make('logo_image')
                                            ->directory('settings')
                                            ->visible(fn ($get) => $get('logo_type') === 'image'),
                                    ]),

                                // TAB 2: Contact Details
                                Forms\Components\Tabs\Tab::make('Contact Details')
                                    ->icon('heroicon-o-phone')
                                    ->schema([
                                        Forms\Components\TextInput::make('whatsapp_number')->prefix('+91'),
                                        Forms\Components\TextInput::make('contact_email')->email(),
                                        Forms\Components\TextInput::make('contact_phone'),
                                        Forms\Components\Textarea::make('contact_address')->columnSpanFull(),
                                    ])->columns(2),

                                // TAB 3: Footer Section
                                Forms\Components\Tabs\Tab::make('Footer Section')
                                    ->icon('heroicon-o-bars-3-bottom-left')
                                    ->schema([
                                        // Removed FileUpload for footer_image here
                                        Forms\Components\FileUpload::make('footer_background_image')
                                            ->label('Background Image')
                                            ->image()
                                            ->directory('settings'),
                                        
                                        Forms\Components\TextInput::make('footer_brand_heading'),
                                        Forms\Components\Textarea::make('footer_text')->rows(3),
                                        Forms\Components\TextInput::make('footer_newsletter_text'),
                                        Forms\Components\TextInput::make('footer_copyright_company'),
                                        
                                        Forms\Components\Section::make('Social Media Links')
                                            ->columns(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('facebook_link')->url(),
                                                Forms\Components\TextInput::make('instagram_link')->url(),
                                                Forms\Components\TextInput::make('twitter_link')->url(),
                                                Forms\Components\TextInput::make('youtube_link')->url(),
                                            ]),
                                    ]),
                            ])->columnSpanFull()
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $setting = Setting::getSiteSettings();
        $setting->update($this->form->getState());
        $this->isEditing = false;
        Notification::make()->success()->title('Settings Saved!')->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Save Changes')->action('save')->visible(fn () => $this->isEditing),
            Action::make('cancel')->label('Cancel')->color('gray')->action('cancelEditing')->visible(fn () => $this->isEditing),
            Action::make('edit')->label('Edit Settings')->action('enableEditing')->visible(fn () => ! $this->isEditing),
        ];
    }
}