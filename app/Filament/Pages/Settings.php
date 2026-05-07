<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static string $view = 'filament.pages.settings';

    public ?Setting $record = null;

    public function mount(): void
    {
        $this->record = Setting::first();
    }

    public function toggleShutdown(): void
    {
        $this->record->update([
            'shutdown_mode' => !$this->record->shutdown_mode,
        ]);

        Notification::make()
            ->title($this->record->shutdown_mode ? 'Site Shutdown Active' : 'Site is now Live')
            ->warning()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('initialize')
                ->label('Initialize Settings')
                ->color('info')
                ->visible(fn () => !$this->record)
                ->action(function () {
                    Setting::create([
                        'site_title' => 'My Boutique',
                        'instagram' => 'https://instagram.com/',
                    ]);
                    return redirect(static::getUrl());
                }),
        ];
    }

    public function editGeneralAction(): Action
    {
        return Action::make('editGeneral')
            ->label('Edit')
            ->icon('heroicon-m-pencil-square')
            ->color('warning')
            ->modalHeading('General Settings')
            ->fillForm(fn () => $this->record?->toArray()) 
            ->form([
                TextInput::make('site_title')->label('Side Title')->required(),
                Textarea::make('about_us')->label('About Us')->required(),
            ])
            ->action(fn (array $data) => $this->updateSettings($data));
    }

    public function editContactAction(): Action
    {
        return Action::make('editContact')
            ->label('Edit')
            ->icon('heroicon-m-pencil-square')
            ->color('warning')
            ->modalHeading('Contact Settings')
            ->fillForm(fn () => $this->record?->toArray())
            ->form([
                Grid::make(2)->schema([
                    TextInput::make('address')->required(),
                    TextInput::make('google_map_link')->label('Google Map URL')->url()->required(),
                    TextInput::make('phone_1')->label('Phone No. 1')->tel()->required(),
                    TextInput::make('phone_2')->label('Phone No. 2')->tel(),
                    TextInput::make('email')->email()->required(),
                ]),
                Grid::make(3)->schema([
                    TextInput::make('facebook')->prefix('https://facebook.com/'),
                    TextInput::make('instagram')->prefix('https://instagram.com/')->required(),
                    TextInput::make('twitter')->label('X (Twitter)')->prefix('https://x.com/'),
                ]),
            ])
            ->action(fn (array $data) => $this->updateSettings($data));
    }

    protected function updateSettings(array $data): void
    {
        $this->record->update($data);
        Notification::make()->title('Settings updated successfully')->success()->send();
    }
}