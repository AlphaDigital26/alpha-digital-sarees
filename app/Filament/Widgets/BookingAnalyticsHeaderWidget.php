<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class BookingAnalyticsHeaderWidget extends Widget
{
    protected static ?int $sort = 2; // Before chart and analytics
    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.booking-analytics-header';
}
