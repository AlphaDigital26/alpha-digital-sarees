<x-filament-panels::page>
    
    @livewire(\App\Filament\Widgets\TopStatsWidget::class)

    <div class="mt-8 mb-4">
        @livewire(\App\Filament\Widgets\BookingsChartWidget::class)
    </div>

    <div class="mt-8 mb-2 flex items-center justify-between">
        <h2 class="text-[16px] font-bold text-gray-600 flex items-center gap-2 uppercase tracking-wide">
            <x-heroicon-o-presentation-chart-line class="w-5 h-5 text-gray-400" />
            Booking Analytics
        </h2>
        <select class="text-sm border-gray-200 rounded-md shadow-sm text-gray-600 focus:ring-primary-500 focus:border-primary-500 py-1.5 pl-3 pr-8">
            <option>All Time</option>
        </select>
    </div>
    @livewire(\App\Filament\Widgets\BookingAnalyticsWidget::class)

    <div class="mt-8 mb-2 flex items-center justify-between">
        <h2 class="text-[16px] font-bold text-gray-600 flex items-center gap-2 uppercase tracking-wide">
            <x-heroicon-o-clock class="w-5 h-5 text-gray-400" />
            User, Queries, Reviews Analytics
        </h2>
        <select class="text-sm border-gray-200 rounded-md shadow-sm text-gray-600 focus:ring-primary-500 focus:border-primary-500 py-1.5 pl-3 pr-8">
            <option>All Time</option>
        </select>
    </div>
    @livewire(\App\Filament\Widgets\UserAnalyticsWidget::class)

    <div class="mt-8 mb-2 flex items-center justify-start">
        <h2 class="text-[16px] font-bold text-gray-600 flex items-center gap-2 uppercase tracking-wide">
            <x-heroicon-o-users class="w-5 h-5 text-gray-400" />
            Users Overview
        </h2>
    </div>
    @livewire(\App\Filament\Widgets\UsersOverviewWidget::class)

</x-filament-panels::page>
