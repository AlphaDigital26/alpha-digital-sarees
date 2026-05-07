<?php

namespace App\Filament\Resources\CarouselResource\Pages;

use App\Filament\Resources\CarouselResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarousels extends ListRecords
{
    protected static string $resource = CarouselResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // This makes the 'New' button open the modal instead of a new page
            Actions\CreateAction::make()
                ->label('New Image')
                ->modalHeading('Upload Carousel Picture')
                ->createAnother(false), 
        ];
    }
}