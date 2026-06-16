<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Pages;

use App\Filament\Dashboard\Resources\SalesRepresentatives\SalesRepresentativeResource;
use App\Filament\Imports\SalesRepresentativeImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListSalesRepresentatives extends ListRecords
{
    protected static string $resource = SalesRepresentativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(SalesRepresentativeImporter::class)
                ->options(fn (): array => [
                    'distributor_id' => Filament::getTenant()->getKey(),
                ]),
            CreateAction::make(),
        ];
    }
}
