<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\PaymentMethods\Schemas;

use App\Models\PaymentMethod;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::nameInput(),
            ]);
    }

    protected static function nameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('resources.payment_method.form.name'))
            ->required()
            ->unique(PaymentMethod::class, 'name', ignoreRecord: true);
    }
}
