<?php

namespace App\Filament\Resources\UserQueryResource\Pages;

use App\Filament\Resources\UserQueryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserQuery extends CreateRecord
{
    protected static string $resource = UserQueryResource::class;
}
