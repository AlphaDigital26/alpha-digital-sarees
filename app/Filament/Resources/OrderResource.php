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
                ->group('Orders')
                ->sort(1) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'new'),

            NavigationItem::make('Processing Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'processing']))
                ->group('Orders')
                ->sort(2) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'processing'),

            NavigationItem::make('Packed Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'packed']))
                ->group('Orders')
                ->sort(3) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'packed'),

            NavigationItem::make('Shipped Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'shipped']))
                ->group('Orders')
                ->sort(4) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'shipped'),

            NavigationItem::make('Delivered Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'delivered']))
                ->group('Orders')
                ->sort(5) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'delivered'),

            NavigationItem::make('Cancelled Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'cancelled']))
                ->group('Orders')
                ->sort(6) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'cancelled'),

            NavigationItem::make('Refund Requests')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'refund_requested']))
                ->group('Orders')
                ->sort(7) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'refund_requested'),

            NavigationItem::make('Refunded Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'refunded']))
                ->group('Orders')
                ->sort(8) 
                ->isActiveWhen(fn () => request()->routeIs(static::getRouteBaseName() . '.index') && request()->query('activeTab') === 'refunded'),

            NavigationItem::make('All Orders')
                ->url(fn (): string => static::getUrl('index', ['activeTab' => 'all']))
                ->group('Orders')
                ->sort(9) 
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
                            'packed' => 'Packed',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                            'refund_requested' => 'Refund Requested',
                            'refunded' => 'Refunded',
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
                        'processing' => 'Processing',
                        'packed' => 'Packed',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled Orders',
                        'refund_requested' => 'Refund Requests',
                        'refunded' => 'Refund Orders',
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
        
        $validTabs = ['new', 'processing', 'packed', 'shipped', 'delivered', 'cancelled', 'refund_requested', 'refunded'];
        
        if (in_array($activeTab, $validTabs)) {
            if ($activeTab === 'refund_requested') {
                $query->whereIn('status', ['refund_requested', 'refund_approved', 'refund_rejected']);
            } else {
                $query->where('status', $activeTab);
            }
            
            // Backward compatibility for any historical data with single 'L'
            if ($activeTab === 'cancelled') {
                $query->orWhere('status', 'canceled');
            }
        }

        return $query;
    }
}