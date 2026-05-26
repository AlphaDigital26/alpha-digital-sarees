<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UserAnalyticsHeaderWidget extends Widget
{
    protected static ?int $sort = 5; // Before UserAnalytics
    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.user-analytics-header';
}
