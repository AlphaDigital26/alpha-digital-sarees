<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\UserQuery;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TopStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::where('status', 'new')->count() ?? 0)
                ->url(\App\Filament\Resources\OrderResource::getUrl('index'))
                ->icon('heroicon-o-arrow-trending-up')
                ->extraAttributes(['class' => 'custom-stat-card top-row-card card-icon-red value-text-red']),
                
            Stat::make('Refund Orders', Order::where('status', 'refunded')->count() ?? 0)
                ->url(\App\Filament\Resources\OrderResource::getUrl('index'))
                ->icon('heroicon-o-receipt-percent')
                ->extraAttributes(['class' => 'custom-stat-card top-row-card card-icon-blue']),
                
            Stat::make('User Queries', UserQuery::where('is_read', false)->count() ?? 0)
                ->url(\App\Filament\Resources\UserQueryResource::getUrl('index'))
                ->icon('heroicon-o-users')
                ->extraAttributes(['class' => 'custom-stat-card top-row-card card-icon-green']),
                
            Stat::make('All Sarees', \App\Models\Product::count() ?? 0)
                ->url(\App\Filament\Resources\ProductResource::getUrl('index'))
                ->icon('heroicon-o-shopping-cart')
                ->extraAttributes(['class' => 'custom-stat-card top-row-card card-icon-purple']),
                
            Stat::make('Rating & Reviews', '0') // No review model yet
                ->url('#')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->extraAttributes(['class' => 'custom-stat-card top-row-card card-icon-yellow']),
        ];
    }
}
