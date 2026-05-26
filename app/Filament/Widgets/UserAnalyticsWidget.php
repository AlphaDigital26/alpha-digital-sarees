<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\UserQuery;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserAnalyticsWidget extends BaseWidget
{
    protected static ?int $sort = 6;

    protected function getStats(): array
    {
        return [
            Stat::make('NEW REGISTRATION', User::whereDate('created_at', '>=', now()->subDays(30))->count())
                ->descriptionIcon('heroicon-o-user-plus', 'before')
                ->color('success')
                ->extraAttributes(['class' => 'custom-stat-card border-t-green centered-stat text-success']),
                
            Stat::make('QUERIES', UserQuery::count())
                ->descriptionIcon('heroicon-o-chat-bubble-left-ellipsis', 'before')
                ->color('primary')
                ->extraAttributes(['class' => 'custom-stat-card border-t-blue centered-stat text-primary']),
                
            Stat::make('REVIEWS', '0')
                ->descriptionIcon('heroicon-o-star', 'before')
                ->color('warning')
                ->extraAttributes(['class' => 'custom-stat-card border-t-yellow centered-stat text-warning']),
        ];
    }
}
