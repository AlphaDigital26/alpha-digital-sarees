<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 8;

    protected function getStats(): array
    {
        return [
            Stat::make('TOTAL USERS', User::count())
                ->extraAttributes(['class' => 'custom-stat-card border-t-cyan centered-stat']),
                
            Stat::make('ACTIVE USERS', User::count()) // Mocking all as active for now
                ->extraAttributes(['class' => 'custom-stat-card border-t-green centered-stat']),
                
            Stat::make('INACTIVE USERS', '0')
                ->extraAttributes(['class' => 'custom-stat-card border-t-yellow centered-stat']),
                
            Stat::make('UNVERIFIED USERS', '0')
                ->extraAttributes(['class' => 'custom-stat-card border-t-red centered-stat']),
        ];
    }
}
