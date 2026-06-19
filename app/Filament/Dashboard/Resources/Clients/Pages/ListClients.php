<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clients\Pages;

use App\Filament\Dashboard\Resources\Clients\ClientResource;
use App\Filament\Imports\ClientImporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListClients extends ListRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(ClientImporter::class)
                ->extraModalFooterActions([
                    Action::make('downloadImportExample')
                        ->label('Baixar exemplo')
                        ->action(fn (): BinaryFileResponse => response()->download(
                            storage_path('imports/clientes.csv'),
                            'clientes.csv',
                        )),
                ])
                ->options(fn (): array => [
                    'distributor_id' => Filament::getTenant()->getKey(),
                ]),
        ];
    }
}
