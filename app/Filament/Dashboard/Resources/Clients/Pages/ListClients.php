<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clients\Pages;

use App\Filament\Dashboard\Resources\Clients\ClientResource;
use App\Filament\Imports\ClientImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(ClientImporter::class)
                ->options(fn (): array => [
                    'distributor_id' => Filament::getTenant()->getKey(),
                ]),
            CreateAction::make(),
        ];
    }
}
