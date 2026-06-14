<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class BookingsChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '280px';

    public ?string $filter = '7_days';
    
    public function getHeading(): string
    {
        return 'Orders Analytics';
    }

    protected function getFilters(): ?array
    {
        return [
            '7_days' => 'Last 7 Days',
            'last_month' => 'Last 30 Days',
            'this_year' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        
        $data = [];
        $labels = [];

        if ($activeFilter === 'this_year') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->startOfMonth()->subMonths($i);
                $labels[] = $date->format('M Y');
                $data[] = Order::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count();
            }
        } elseif ($activeFilter === 'last_month') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                $data[] = Order::whereDate('created_at', $date->toDateString())->count();
            }
        } else {
            // default to 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('M d');
                $data[] = Order::whereDate('created_at', $date->toDateString())->count();
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'borderColor' => '#800020', // primary burgundy
                    'backgroundColor' => 'rgba(128, 0, 32, 0.1)', // Subtle red fill
                    'borderWidth' => 2,
                    'pointBackgroundColor' => '#ffffff',
                    'pointBorderColor' => '#800020',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'tension' => 0.4, // Smooth curve
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(128, 128, 128, 0.1)', // Very faint grid line
                        'drawBorder' => false,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false, // Remove vertical grid lines
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
