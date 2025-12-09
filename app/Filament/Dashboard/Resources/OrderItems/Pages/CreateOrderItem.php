<?php

namespace App\Filament\Dashboard\Resources\OrderItems\Pages;

use App\Filament\Dashboard\Resources\OrderItems\OrderItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderItem extends CreateRecord
{
    protected static string $resource = OrderItemResource::class;
}
