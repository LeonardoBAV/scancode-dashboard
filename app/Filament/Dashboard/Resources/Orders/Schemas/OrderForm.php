<?php

namespace App\Filament\Dashboard\Resources\Orders\Schemas;

use App\Enums\OrderStatusEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options(OrderStatusEnum::class)
                    ->default('pending')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Select::make('client_id')
                    ->relationship('client', 'id')
                    ->required(),
                Select::make('sales_representative_id')
                    ->relationship('salesRepresentative', 'name')
                    ->required(),
                Select::make('payment_method_id')
                    ->relationship('paymentMethod', 'name'),
            ]);
    }
}
