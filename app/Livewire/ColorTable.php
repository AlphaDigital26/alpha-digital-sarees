<?php
namespace App\Livewire;

use App\Models\Color;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ColorTable extends BaseWidget
{
    protected static ?string $heading = 'Colors';

    public function table(Table $table): Table
    {
        return $table
            ->query(Color::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->model(Color::class)
                    ->form([
                        Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true),
                    ])
                    ->label('Add Color'),
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