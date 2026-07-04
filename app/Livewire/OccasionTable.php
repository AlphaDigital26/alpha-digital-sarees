<?php

namespace App\Livewire;

use App\Models\Occasion;
use Filament\Forms;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;


class OccasionTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected function getOccasionFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            Forms\Components\FileUpload::make('image')
                ->image()
                ->directory('occasions')

                ->helperText('Image will be converted to WebP automatically.')
                ->nullable(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Occasion::query())
            ->heading('Occasions')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->model(Occasion::class)
                    ->form($this->getOccasionFormSchema()),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form($this->getOccasionFormSchema()),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public function render()
    {
        // This cleanly renders the standalone Filament table
        return <<<'HTML'
        <div>
            {{ $this->table }}
        </div>
        HTML;
    }
}