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

    protected static ?string $navigationLabel = 'Sarees';

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
        ->contentGrid([ 'md' => 2, 'xl' => 3 ]) // Creates the grid from your image
        ->columns([
            \Filament\Tables\Columns\Layout\Stack::make([
                \Filament\Tables\Columns\ImageColumn::make('image')
                    ->height('300px')->width('100%')
                    ->extraImgAttributes(['class' => 'object-cover w-full h-full rounded-2xl']),
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->weight('bold')->size('lg'),
                \Filament\Tables\Columns\TextColumn::make('price')
                    ->money('INR'),
            ]),
        ])
        ->headerActions([
            // This is the "Add Product" button that opens the modal (Image 2)
            \Filament\Tables\Actions\CreateAction::make()
                ->label('New Product')
                ->modalHeading('Add New Saree Design')
                ->slideOver() // Use slideOver() if you want the side-drawer look
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
