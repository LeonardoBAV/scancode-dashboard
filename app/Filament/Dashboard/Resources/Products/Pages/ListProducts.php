<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Products\Pages;

use App\Filament\Dashboard\Resources\Products\ProductResource;
use App\Filament\Imports\ProductImporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('downloadImportExample')
                ->label('Baixar exemplo')
                ->action(fn (): BinaryFileResponse => response()->download(
                    storage_path('imports/produtos.csv'),
                    'produtos.csv',
                )),
            ImportAction::make()
                ->importer(ProductImporter::class)
                ->options(fn (): array => [
                    'distributor_id' => Filament::getTenant()->getKey(),
                ]),
        ];
    }
}
