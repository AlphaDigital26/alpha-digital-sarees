<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        // Go back to the previous list URL (preserving page/sort/filter query params)
        // and append a fragment so the browser scrolls directly to the edited row.
        $base = $this->previousUrl ?? $this->getResource()::getUrl('index');

        // Strip any existing fragment from the base URL first
        $base = preg_replace('/#.*$/', '', $base);

        return $base . '#table-row-' . $this->record->getKey();
    }


}