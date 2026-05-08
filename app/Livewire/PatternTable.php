<?php
namespace App\Livewire;

use App\Models\Pattern;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PatternTable extends BaseWidget
{
    protected static ?string $heading = 'Patterns';

    public function table(Table $table): Table
    {
        return $table
            ->query(Pattern::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->model(Pattern::class)
                    ->form([
                        Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true),
                    ])
                    ->label('Add Pattern'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}