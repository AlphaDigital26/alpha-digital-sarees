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
use Illuminate\Database\Eloquent\Builder;

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
            Forms\Components\Section::make('Order Status')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->options([
                            'new' => 'New Order (Placed)',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'refunded' => 'Refunded',
                            'canceled' => 'Canceled',
                        ])
                        ->required()
                        ->helperText('Updating the status here will automatically update the Order History timeline on both the Admin Dashboard and the Customer Tracking page.'),
                ])->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\Layout\View::make('filament.tables.columns.order-card'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter by Status')
                    ->options([
                        'new' => 'New Orders',
                        'refunded' => 'Refund Orders',
                        'canceled' => 'Canceled Orders',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->extraAttributes(['style' => 'display: none !important;']),
                Tables\Actions\EditAction::make()->extraAttributes(['style' => 'display: none !important;']),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['customer', 'items.product'])
            ->where(function (Builder $q) {
                $q->whereNotIn('payment_status', ['pending', 'failed'])
                  ->orWhereNull('payment_status');
            });

        $activeTab = request()->query('activeTab');
        
        if ($activeTab === 'new') {
            $query->where('status', 'new');
        } elseif ($activeTab === 'refunded') {
            $query->whereIn('status', ['canceled', 'refunded']);
        }

        return $query;
    }
}