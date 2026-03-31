<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Events\Pages;

use App\Filament\Dashboard\Resources\Events\EventResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
