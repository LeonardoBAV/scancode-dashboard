<?php

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Pages;

use App\Filament\Dashboard\Resources\SalesRepresentatives\SalesRepresentativeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSalesRepresentative extends ViewRecord
{
    protected static string $resource = SalesRepresentativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
