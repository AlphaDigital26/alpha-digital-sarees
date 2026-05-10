<?php
namespace App\Livewire;

use App\Models\Fabric;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FabricTable extends BaseWidget
{
    protected static ?string $heading = 'Fabrics';

    public function table(Table $table): Table
    {
        return $table
            ->query(Fabric::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->model(Fabric::class)
                    ->form([
                        Forms\Components\TextInput::make('name')->required()->unique(ignoreRecord: true),
                    ])
                    ->label('Add Fabric'),
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