<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\PaymentMethods\Pages;

use App\Filament\Dashboard\Resources\PaymentMethods\PaymentMethodResource;
use App\Filament\Imports\PaymentMethodImporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListPaymentMethods extends ListRecords
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(PaymentMethodImporter::class)
                ->options(fn (): array => [
                    'distributor_id' => Filament::getTenant()->getKey(),
                ]),
            CreateAction::make(),
        ];
    }
}
