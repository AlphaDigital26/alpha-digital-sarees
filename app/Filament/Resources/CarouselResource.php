<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarouselResource\Pages; // Add this line
use App\Models\Carousel;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\FileUpload;

class CarouselResource extends Resource
{
    protected static ?string $model = Carousel::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')
                    ->image()
                    ->directory('carousels')
                    ->required(),
            ]);
    }

public static function table(Table $table): Table
{
    return $table
        ->contentGrid([
            'md' => 2,
            'xl' => 3,
        ])
        ->columns([
            Stack::make([
                ImageColumn::make('image')
                    ->height('250px') // Set a fixed height
                    ->width('100%')  // Set width to 100%
                    ->extraImgAttributes([
                        'class' => 'object-cover w-full h-full rounded-lg', // This makes it cover the block
                    ]),
            ]),
        ])
        ->filters([])
        ->actions([
            DeleteAction::make()
                ->button()
                ->color('danger'),
        ]);

    }
    
    public static function getPages(): array
{
    return [
        'index' => Pages\ListCarousels::route('/'),
    ];
}
}