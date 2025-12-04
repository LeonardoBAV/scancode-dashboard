<?php

namespace App\Filament\Dashboard\Resources\PaymentMethods\Pages;

use App\Filament\Dashboard\Resources\PaymentMethods\PaymentMethodResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    protected static string $resource = PaymentMethodResource::class;
}
