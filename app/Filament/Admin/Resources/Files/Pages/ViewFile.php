<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Files\Pages;

use App\Filament\Admin\Resources\Files\FileResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFile extends ViewRecord
{
    protected static string $resource = FileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
        ];
    }
}
