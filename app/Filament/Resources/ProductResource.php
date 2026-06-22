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
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // CHANGED: Replaced Basic Information section with a Tabbed layout
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Saree Name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($state) {
                                    // 1. URL Slug and Title Optimization (Remove connectors to save space)
                                    $seoName = preg_replace('/\b(with|and|for|in|on|the|a|an)\b/i', '', $state);
                                    $seoName = preg_replace('/\s+/', ' ', $seoName);
                                    $seoName = trim($seoName);
                                    
                                    $set('slug', \Illuminate\Support\Str::slug($seoName));
                                    
                                    // Smart Meta Title (Max 60 chars) - Must include Alpha Digital
                                    $title1 = 'Buy ' . $seoName . ' | Alpha Digital';
                                    $title2 = $seoName . ' | Alpha Digital';
                                    
                                    if (strlen($title1) <= 60) {
                                        $set('meta_title', $title1);
                                    } elseif (strlen($title2) <= 60) {
                                        $set('meta_title', $title2);
                                    } else {
                                        // Force Alpha Digital at the end, truncate the name without breaking words
                                        $maxLength = 60 - 16; // 44 chars max for the name part
                                        $truncatedName = mb_substr($seoName, 0, $maxLength);
                                        $lastSpace = mb_strrpos($truncatedName, ' ');
                                        if ($lastSpace !== false) {
                                            $truncatedName = mb_substr($truncatedName, 0, $lastSpace);
                                        }
                                        $set('meta_title', trim($truncatedName) . ' | Alpha Digital');
                                    }
                                    
                                    // 2. Meta Description Optimization (Keep natural grammar from original name)
                                    $lowerState = strtolower($state);
                                    $desc1 = 'Shop our exclusive ' . $lowerState . ' at Alpha Digital. Discover premium, authentic sarees and experience the perfect blend of tradition and modern elegance.';
                                    $desc2 = 'Shop ' . $lowerState . ' at Alpha Digital. Discover premium, authentic sarees.';
                                    $desc3 = 'Buy ' . $lowerState . ' online at Alpha Digital.';
                                    
                                    if (strlen($desc1) <= 160) {
                                        $set('meta_description', $desc1);
                                    } elseif (strlen($desc2) <= 160) {
                                        $set('meta_description', $desc2);
                                    } elseif (strlen($desc3) <= 160) {
                                        $set('meta_description', $desc3);
                                    } else {
                                        $set('meta_description', \Illuminate\Support\Str::limit($desc3, 157, '...'));
                                    }
                                    
                                    $set('meta_keywords', \Illuminate\Support\Str::limit(strtolower($seoName) . ', authentic sarees, alpha digital, Indian ethnic wear', 255, ''));
                                }
                            }),

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
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                            ->maxSize(5120)
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
                    
                Forms\Components\Section::make('SEO Settings')
                    ->description('Manage search engine optimization for this product')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->helperText('The unique URL identifier. Auto-generated if left blank.'),
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(60)
                            ->helperText('Optimal length is under 60 characters.'),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(160)
                            ->helperText('Optimal length is under 160 characters. This appears in search results.'),
                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->helperText('Comma separated (e.g. linen saree, handwoven, summer)'),
                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url()
                            ->helperText('Leave blank to use the default product URL.'),
                    ])->columns(1),
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
            ->defaultSort('created_at', 'desc')
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