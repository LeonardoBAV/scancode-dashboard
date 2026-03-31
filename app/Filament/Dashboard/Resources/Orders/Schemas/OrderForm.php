<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::eventInput(),
                self::clientInput(),
                self::salesRepresentativeInput(),
                self::paymentMethodInput(),
                self::notesInput(),
            ]);
    }

    protected static function eventInput(): Select
    {
        return Select::make('event_id')
            ->label(__('resources.order.form.event'))
            ->relationship('event', 'name')
            ->searchable()
            ->preload()
            ->required();
    }

    protected static function clientInput(): Select
    {
        return Select::make('client_id')
            ->label(__('resources.order.form.client'))
            ->relationship('client', 'fantasy_name')
            ->searchable()
            ->preload()
            ->required();
    }

    protected static function salesRepresentativeInput(): Select
    {
        return Select::make('sales_representative_id')
            ->label(__('resources.order.form.sales_representative'))
            ->relationship('salesRepresentative', 'name')
            ->searchable()
            ->preload()
            ->required();
    }

    protected static function paymentMethodInput(): Select
    {
        return Select::make('payment_method_id')
            ->label(__('resources.order.form.payment_method'))
            ->relationship('paymentMethod', 'name')
            ->searchable()
            ->preload()
            ->columnSpanFull()
            ->required();
    }

    protected static function notesInput(): Textarea
    {
        return Textarea::make('notes')
            ->label(__('resources.order.form.notes'))
            ->columnSpanFull()
            ->rows(4);
    }
}
