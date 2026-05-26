<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UsersOverviewHeaderWidget extends Widget
{
    protected static ?int $sort = 7; // Before UsersOverview
    protected int | string | array $columnSpan = 'full';
    protected static string $view = 'filament.widgets.users-overview-header';
}
