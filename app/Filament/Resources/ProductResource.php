<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([

            Forms\Components\TextInput::make('name')
                ->required(),

            Forms\Components\TextInput::make('fabric')
                ->required(),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->required(),

            Forms\Components\Textarea::make('description'),

            Forms\Components\TextInput::make('stock')
                ->numeric()
                ->default(0),

            Forms\Components\FileUpload::make('image')
                ->image()
                ->directory('products'),

            Forms\Components\Toggle::make('is_featured')

        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('fabric'),
            Tables\Columns\TextColumn::make('price'),
            Tables\Columns\IconColumn::make('is_featured')->boolean(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
