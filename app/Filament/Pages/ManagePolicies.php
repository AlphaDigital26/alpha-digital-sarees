<?php

namespace App\Filament\Pages;

use App\Models\PolicyPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManagePolicies extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Manage Policies';
    protected static ?int $navigationSort = 10;
    // protected static ?string $navigationGroup = 'Site Management';
    protected static string $view = 'filament.pages.manage-story'; // We can reuse the view since it just renders the form

    public ?array $data = [];
    public bool $isEditing = false;

    public function mount(): void {
        $this->form->fill(PolicyPage::firstOrCreate(['id' => 1])->toArray());
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Policies')->tabs([
                Forms\Components\Tabs\Tab::make('Privacy Policy')->schema([
                    Forms\Components\RichEditor::make('privacy_policy')->label('')->columnSpanFull(),
                ]),
                Forms\Components\Tabs\Tab::make('Terms and Conditions')->schema([
                    Forms\Components\RichEditor::make('terms_and_conditions')->label('')->columnSpanFull(),
                ]),
                Forms\Components\Tabs\Tab::make('Shipping Policy')->schema([
                    Forms\Components\RichEditor::make('shipping_policy')->label('')->columnSpanFull(),
                ]),
                Forms\Components\Tabs\Tab::make('Refund Policy')->schema([
                    Forms\Components\RichEditor::make('refund_policy')->label('')->columnSpanFull(),
                ]),
                Forms\Components\Tabs\Tab::make('FAQs')->schema([
                    Forms\Components\Repeater::make('faqs')->label('Frequently Asked Questions')->schema([
                        Forms\Components\TextInput::make('question')->required(),
                        Forms\Components\Textarea::make('answer')->required(),
                    ])->columnSpanFull()->reorderable(true),
                ]),
            ])->columnSpanFull()->disabled(fn () => !$this->isEditing)
        ])->statePath('data');
    }

    public function enableEditing(): void
    {
        $this->isEditing = true;
    }

    public function cancelEditing(): void
    {
        $this->isEditing = false;
        $this->form->fill(PolicyPage::firstOrCreate(['id' => 1])->toArray());
    }

    public function save(): void {
        if (!$this->isEditing) return;

        PolicyPage::updateOrCreate(['id' => 1], $this->form->getState());
        $this->isEditing = false;
        Notification::make()->success()->title('Policies Saved!')->send();
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
