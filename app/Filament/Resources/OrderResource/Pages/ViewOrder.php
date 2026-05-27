<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;
    
    protected static string $view = 'filament.resources.order-resource.pages.view-order';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function refundOrder()
    {
        $this->record->update([
            'status' => 'refunded',
            'payment_status' => 'refunded',
        ]);
        
        \Filament\Notifications\Notification::make()
            ->title('Order Refunded Successfully')
            ->success()
            ->send();
    }
}
