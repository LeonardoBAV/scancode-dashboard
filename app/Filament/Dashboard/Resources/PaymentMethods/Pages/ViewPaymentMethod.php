<?php

namespace App\Filament\Dashboard\Resources\PaymentMethods\Pages;

use App\Filament\Dashboard\Resources\PaymentMethods\PaymentMethodResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPaymentMethod extends ViewRecord
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
