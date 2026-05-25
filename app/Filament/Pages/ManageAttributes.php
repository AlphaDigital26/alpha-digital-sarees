<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ManageAttributes extends Page
{
    // The icon and name that will appear in your sidebar
    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $title = 'Manage Fabrics, Colors & Patterns';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.pages.manage-attributes';

    protected static ?string $navigationLabel = 'Saree Attributes';
}