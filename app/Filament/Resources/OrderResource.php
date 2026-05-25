<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Navigation\NavigationItem; 
use Filament\Tables\Filters\SelectFilter; 

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('New Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'new']))
                // Removed the icon line here
                ->group('Orders')
                ->sort(1) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'new'),

            NavigationItem::make('Refund Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'refunded']))
                // Removed the icon line here
                ->group('Orders')
                ->sort(2) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'refunded'),

            NavigationItem::make('All Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'all']))
                // Removed the icon line here
                ->group('Orders')
                ->sort(3) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && (request()->query('activeTab') === 'all' || blank(request()->query('activeTab')))),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Form structure will go here later
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('customer.name')->label('Customer')->searchable(),
                Tables\Columns\TextColumn::make('total_amount')->money('INR')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'refunded' => 'danger',
                        'delivered' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M d, Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter by Status')
                    ->options([
                        'new' => 'New Orders',
                        'refunded' => 'Refund Orders',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}