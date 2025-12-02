<?php

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Pages;

use App\Filament\Dashboard\Resources\SalesRepresentatives\SalesRepresentativeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalesRepresentatives extends ListRecords
{
    protected static string $resource = SalesRepresentativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
