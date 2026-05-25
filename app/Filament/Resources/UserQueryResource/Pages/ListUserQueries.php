<?php

namespace App\Filament\Resources\UserQueryResource\Pages;

use App\Filament\Resources\UserQueryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserQueries extends ListRecords
{
    protected static string $resource = UserQueryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
