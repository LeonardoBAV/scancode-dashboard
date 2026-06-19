<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages;

use BackedEnum;
use Filament\Pages\Page;
use UnitEnum;

class Downloads extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'phosphor-download';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.dashboard.pages.downloads';

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return __('filament.support.navigation_group');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.support.downloads');
    }

    public function getTitle(): string
    {
        return __('filament.support.downloads');
    }
}
