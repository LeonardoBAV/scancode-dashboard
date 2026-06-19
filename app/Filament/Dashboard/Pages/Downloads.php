<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages;

use App\Enums\FileTypeEnum;
use App\Models\File;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
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

    public function getHeading(): string
    {
        return __('filament.support.downloads_page.heading');
    }

    public function getSubheading(): ?string
    {
        return __('filament.support.downloads_page.subheading');
    }

    /**
     * @return Collection<int, File>
     */
    public function getOtherFiles(): Collection
    {
        return File::query()
            ->whereNotIn('type', [FileTypeEnum::APP, FileTypeEnum::DESKTOP])
            ->latest('id')
            ->get();
    }

    public function getAppFile(): ?File
    {
        return File::query()
            ->where('type', FileTypeEnum::APP)
            ->latest('id')
            ->first();
    }

    public function getDesktopFile(): ?File
    {
        return File::query()
            ->where('type', FileTypeEnum::DESKTOP)
            ->latest('id')
            ->first();
    }
}
