<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingAnalyticsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    // We can use a custom view or just Filament's default
    // If we want a header "Booking Analytics", we can inject it via custom view 
    // or rely on the Dashboard page to render the headings.
    
    protected function getStats(): array
    {
        $totalSum = Order::sum('total_amount') ?? 0;
        $activeSum = Order::whereNotIn('status', ['cancelled', 'refunded'])->sum('total_amount') ?? 0;
        $cancelledSum = Order::where('status', 'cancelled')->sum('total_amount') ?? 0;

        return [
            Stat::make('TOTAL ORDERS', Order::count())
                ->description('₹' . number_format($totalSum))
                ->descriptionColor('primary')
                ->extraAttributes(['class' => 'custom-stat-card border-t-blue']),
                
            Stat::make('ACTIVE ORDERS', Order::whereNotIn('status', ['cancelled', 'refunded'])->count())
                ->description('₹' . number_format($activeSum))
                ->descriptionColor('success')
                ->extraAttributes(['class' => 'custom-stat-card border-t-green']),
                
            Stat::make('CANCELLED ORDERS', Order::where('status', 'cancelled')->count())
                ->description('₹' . number_format($cancelledSum))
                ->descriptionColor('danger')
                ->extraAttributes(['class' => 'custom-stat-card border-t-red']),
        ];
    }
}
