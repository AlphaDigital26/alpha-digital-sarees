<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $navigationLabel = 'Sarees';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // CHANGED: Replaced Basic Information section with a Tabbed layout
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Saree Name')
                            ->required(),

                        Forms\Components\Tabs::make('Product Details')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Product Description')
                                    ->schema([
                                        Forms\Components\RichEditor::make('description')
                                            ->label('')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Specification & Dimension')
                                    ->schema([
                                        Forms\Components\RichEditor::make('specifications')
                                            ->label('')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                    ]),
                                Forms\Components\Tabs\Tab::make('Care & Maintenance')
                                    ->schema([
                                        Forms\Components\RichEditor::make('care_instructions')
                                            ->label('')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList', 'orderedList']),
                                    ]),
                            ])->columnSpanFull(),
                    ])->columns(1),

                Forms\Components\Section::make('Pricing & Inventory')
                    ->schema([
                        Forms\Components\TextInput::make('current_price')
                            ->label('Retail Price')
                            ->numeric()
                            ->prefix('₹')
                            ->required(),
                            
                        Forms\Components\TextInput::make('original_price')
                            ->label('Original Price')
                            ->numeric()
                            ->prefix('₹'),
                            
                        Forms\Components\TextInput::make('stock')
                            ->label('Stock Quantity')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),

                Forms\Components\Section::make('Saree Attributes')
                    ->schema([
                        // REMOVED "+" button: Now purely pulls from your Manage Attributes page
                        Forms\Components\Select::make('fabric_id')
                            ->relationship('fabric', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Fabric'),
                            
                        Forms\Components\Select::make('color_id')
                            ->relationship('color', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Primary Color'),
                            
                        Forms\Components\Select::make('pattern_id')
                            ->relationship('pattern', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Pattern'),
                            
                        Forms\Components\Select::make('occasion')
                            ->label('Occasion')
                            ->options(\App\Models\Occasion::pluck('name', 'name'))
                            ->searchable(),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Highlights')
                    ->schema([
                        // CHANGED: Upgraded File Upload to handle multiple images
                        Forms\Components\FileUpload::make('images')
                            ->label('Product Gallery')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->panelLayout('grid')
                            ->directory('products')
                            ->disk('public')
                            ->columnSpanFull(),
                            
                        Forms\Components\Toggle::make('is_new')
                            ->label('Mark as New Arrival'),
                            
                        Forms\Components\Toggle::make('is_best_seller')
                            ->label('Mark as Best Seller'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Shows a small thumbnail of the first image
                \Filament\Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->state(function ($record) {
                        return is_array($record->images) && count($record->images) > 0 ? $record->images[0] : null;
                    })
                    ->square(),
                
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Saree Name')
                    ->searchable()
                    ->sortable()
                    ->limit(40) // Limits the text to 40 characters so the column stays fixed
                    ->tooltip(fn ($record) => $record->name) // Shows the full name when you hover over it
                    ->weight('bold'),
                    
                \Filament\Tables\Columns\TextColumn::make('current_price')
                    ->label('Price')
                    ->money('INR')
                    ->sortable(),
                    
                \Filament\Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),
                    
                // Pulls the name directly from the Fabric table
                \Filament\Tables\Columns\TextColumn::make('fabric.name')
                    ->label('Fabric')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\IconColumn::make('is_new')
                    ->label('New Arrival')
                    ->boolean(),
                    
                \Filament\Tables\Columns\IconColumn::make('is_best_seller')
                    ->label('Best Seller')
                    ->boolean(),
            ])
            ->filters([
                // You can add table filters here later if needed
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}