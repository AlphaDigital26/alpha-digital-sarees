<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class BookingsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Bookings Over Last 7 Days';
    protected static ?int $sort = 3; // Between Top Stats and Analytics
    
    // We want it full width or half width? Let's make it full width
    protected int | string | array $columnSpan = 'full';
    
    // Reduce height
    protected static ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Generate data for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');
            $data[] = Order::whereDate('created_at', $date->toDateString())->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Bookings',
                    'data' => $data,
                    'backgroundColor' => '#800020', // primary burgundy
                    'borderColor' => '#800020',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
