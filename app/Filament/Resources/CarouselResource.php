<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarouselResource\Pages;
use App\Models\Carousel;
use Filament\Forms; // <-- ADDED THIS IMPORT
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables; // <-- ADDED THIS IMPORT FOR CLEANER CODE
use Filament\Tables\Table;

class CarouselResource extends Resource
{
    protected static ?string $model = Carousel::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?int $navigationSort = 8;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Carousel Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('Desktop Slide Image')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                            ->maxSize(5120)
                            ->directory('carousels')
                            ->required()
                            ->helperText('Landscape orientation recommended (16:9 ratio). Used on all screen sizes unless a mobile image is provided.')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image_mobile')
                            ->label('Mobile Slide Image (Optional – Portrait 4:5)')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                            ->maxSize(5120)
                            ->directory('carousels/mobile')
                            ->helperText('Upload a portrait image (4:5 ratio, e.g. 900×1125 px) for mobile devices (≤ 768 px wide). If left blank, the desktop image is used on mobile too.')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image_tablet')
                            ->label('Tablet Slide Image (Optional – Landscape 4:3)')
                            ->image()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                            ->maxSize(5120)
                            ->directory('carousels/tablet')
                            ->helperText('Upload a landscape image (4:3 ratio, e.g. 1280×960 px) for tablet devices (769 – 1024 px wide). If left blank, the desktop image is used on tablet too.')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Overlay Content (Optional)')
                    ->description('Leave these blank if you only want to show the image.')
                    ->schema([
                        Forms\Components\TextInput::make('sub_heading')
                            ->label('Sub Heading (Small text above main heading)'),

                        Forms\Components\TextInput::make('heading')
                            ->label('Main Heading'),

                        Forms\Components\Textarea::make('text')
                            ->label('Description Text')
                            ->columnSpanFull(),
                            
                        Forms\Components\TextInput::make('button_text')
                            ->label('Button Text (e.g., Shop Now)'),
                            
                        Forms\Components\TextInput::make('button_link')
                            ->label('Button URL (e.g., /shop)')
                            ->url(), 
                    ])->columns(2),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Visible on Website')
                            ->default(true),
                            
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Display Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers show first (e.g., 0, 1, 2)'),
                    ])->columns(2),
            ]);
    }

public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // CHANGED: Made the image much larger and wider to fit a banner style
                \Filament\Tables\Columns\ImageColumn::make('image')
                    ->label('Slide Preview')
                    ->height('100px')
                    ->width('250px')
                    ->extraImgAttributes(['class' => 'object-cover rounded-lg shadow-sm'])
                    ->url(fn ($record) => asset('storage/' . $record->image))
                    ->openUrlInNewTab(),
                    
                \Filament\Tables\Columns\TextColumn::make('heading')
                    ->searchable()
                    ->weight('bold')
                    ->placeholder('No heading'),
                    
                \Filament\Tables\Columns\IconColumn::make('image_mobile')
                    ->boolean()
                    ->label('Mobile'),

                \Filament\Tables\Columns\IconColumn::make('image_tablet')
                    ->boolean()
                    ->label('Tablet'),

                \Filament\Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                    
                \Filament\Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Order'),
            ])
            ->defaultSort('sort_order', 'asc') 
            ->filters([
                //
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarousels::route('/'),
            'create' => Pages\CreateCarousel::route('/create'), // <-- RESTORED CREATE ROUTE
            'edit' => Pages\EditCarousel::route('/{record}/edit'), // <-- RESTORED EDIT ROUTE
        ];
    }
}