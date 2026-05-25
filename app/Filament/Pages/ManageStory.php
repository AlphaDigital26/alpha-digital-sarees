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
    protected static string $view = 'filament.pages.manage-story';

    public ?array $data = [];

    public function mount(): void {
        $this->form->fill(Story::firstOrCreate(['id' => 1])->toArray());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Main Hero')->schema([
                Forms\Components\FileUpload::make('main_image')->directory('stories'),
                Forms\Components\TextInput::make('main_heading'),
                Forms\Components\Textarea::make('para_1'),
            ]),
            Forms\Components\Section::make('Craftsmanship')->schema([
                Forms\Components\FileUpload::make('control_image_1')->directory('stories'),
                Forms\Components\FileUpload::make('control_image_2')->directory('stories'),
                Forms\Components\TextInput::make('heading_2'),
                Forms\Components\Textarea::make('para_2'),
            ]),
            Forms\Components\Section::make('Journey & Content')->schema([
                Forms\Components\TextInput::make('heading_3'),
                Forms\Components\RichEditor::make('text_3'),
                Forms\Components\FileUpload::make('control_image_3')->directory('stories'),
                Forms\Components\Grid::make(4)->schema([
                    Forms\Components\FileUpload::make('journey_img_1'),
                    Forms\Components\FileUpload::make('journey_img_2'),
                    Forms\Components\FileUpload::make('journey_img_3'),
                    Forms\Components\FileUpload::make('journey_img_4'),
                ])
            ]),
        ])->statePath('data');
    }

    public function save(): void {
        Story::updateOrCreate(['id' => 1], $this->form->getState());
        Notification::make()->success()->title('Saved!')->send();
    }

    protected function getFormActions(): array {
        return [Action::make('save')->label('Save Changes')->action('save')];
    }
}