<?php

namespace App\Filament\Pages;

use App\Models\Story;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManageStory extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Edit Story Page';
    protected static ?int $navigationSort = 9;
    protected static string $view = 'filament.pages.manage-story';

    public ?array $data = [];

    public bool $isEditing = false;

    public function mount(): void {
        $this->form->fill(Story::firstOrCreate(['id' => 1])->toArray());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Main Hero')->schema([
                    Forms\Components\FileUpload::make('main_image')
                        ->label('Desktop Hero Image')
                        ->directory('stories')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                        ->helperText('Landscape orientation recommended (16:9 ratio).'),
                        
                    Forms\Components\FileUpload::make('main_image_mobile')
                        ->label('Mobile Hero Image (Optional – Portrait 4:5)')
                        ->directory('stories/mobile')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                        ->helperText('Upload a portrait image (4:5 ratio) for mobile devices. If left blank, the desktop image is used.'),

                    Forms\Components\FileUpload::make('main_image_tablet')
                        ->label('Tablet Hero Image (Optional – Landscape 4:3)')
                        ->directory('stories/tablet')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                        ->helperText('Upload a landscape image (4:3 ratio) for tablet devices. If left blank, the desktop image is used.'),

                    Forms\Components\TextInput::make('main_heading'),
                    Forms\Components\Textarea::make('para_1'),
                ]),
                Forms\Components\Section::make('Craftsmanship')->schema([
                    Forms\Components\FileUpload::make('control_image_1')->directory('stories')->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg']),
                    Forms\Components\FileUpload::make('control_image_2')->directory('stories')->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg']),
                    Forms\Components\TextInput::make('heading_2'),
                    Forms\Components\Textarea::make('para_2'),
                ]),
                Forms\Components\Section::make('Journey & Content')->schema([
                    Forms\Components\TextInput::make('heading_3'),
                    Forms\Components\RichEditor::make('text_3'),
                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\FileUpload::make('journey_img_1')->directory('stories')->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg']),
                        Forms\Components\FileUpload::make('journey_img_2')->directory('stories')->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg']),
                        Forms\Components\FileUpload::make('journey_img_3')->directory('stories')->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg']),
                        Forms\Components\FileUpload::make('journey_img_4')->directory('stories')->image()->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg']),
                    ])
                ]),
            ])->disabled(fn () => !$this->isEditing)
        ])->statePath('data');
    }

    public function enableEditing(): void
    {
        $this->isEditing = true;
    }

    public function cancelEditing(): void
    {
        $this->isEditing = false;
        $this->form->fill(Story::firstOrCreate(['id' => 1])->toArray());
    }

    public function save(): void {
        if (!$this->isEditing) return;

        $story = Story::updateOrCreate(['id' => 1], $this->form->getState());
        
        // Refresh form state with the latest DB values (e.g., optimized .webp paths)
        $this->form->fill($story->toArray());
        
        $this->isEditing = false;
        Notification::make()->success()->title('Saved!')->send();
    }

    protected function getFormActions(): array {
        if ($this->isEditing) {
            return [
                Action::make('save')->label('Save Changes')->submit('save')->color('primary'),
                Action::make('cancel')->label('Cancel')->action('cancelEditing')->color('gray'),
            ];
        }

        return [
            Action::make('edit')->label('Edit')->action('enableEditing')->color('primary'),
        ];
    }
}