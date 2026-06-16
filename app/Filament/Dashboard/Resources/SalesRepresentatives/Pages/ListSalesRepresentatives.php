<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Pages;

use App\Filament\Dashboard\Resources\SalesRepresentatives\SalesRepresentativeResource;
use App\Filament\Imports\SalesRepresentativeImporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListSalesRepresentatives extends ListRecords
{
    protected static string $resource = SalesRepresentativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('downloadImportExample')
                ->label('Baixar exemplo')
                ->action(fn (): BinaryFileResponse => response()->download(
                    storage_path('imports/representantes.csv'),
                    'representantes.csv',
                )),
            ImportAction::make()
                ->importer(SalesRepresentativeImporter::class)
                ->options(fn (): array => [
                    'distributor_id' => Filament::getTenant()->getKey(),
                ]),
        ];
    }
}
