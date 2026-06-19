<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\PaymentMethods\Pages;

use App\Filament\Dashboard\Resources\PaymentMethods\PaymentMethodResource;
use App\Filament\Imports\PaymentMethodImporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ListPaymentMethods extends ListRecords
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ImportAction::make()
                ->importer(PaymentMethodImporter::class)
                ->extraModalFooterActions([
                    Action::make('downloadImportExample')
                        ->label('Baixar exemplo')
                        ->action(fn (): BinaryFileResponse => response()->download(
                            storage_path('imports/metodos-de-pagamento.csv'),
                            'metodos-de-pagamento.csv',
                        )),
                ])
                ->options(fn (): array => [
                    'distributor_id' => Filament::getTenant()->getKey(),
                ]),
        ];
    }
}
