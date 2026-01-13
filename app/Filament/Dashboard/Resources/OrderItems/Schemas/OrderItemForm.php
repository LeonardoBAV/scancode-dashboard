<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\OrderItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::productNameInput(),
                self::priceInput(),
                self::qtyInput(),
                self::notesInput(),
            ]);
    }

    protected static function productNameInput(): Select
    {
        return Select::make('product_id')
            ->label(__('resources.order_item.form.product_name'))
            ->relationship('product', 'name')
            ->columnSpanFull()
            ->required();
    }

    protected static function priceInput(): TextInput
    {
        return TextInput::make('price')
            ->label(__('resources.order_item.form.price'))
            ->prefix('$')
            ->required()
            ->numeric();
    }

    protected static function qtyInput(): TextInput
    {
        return TextInput::make('qty')
            ->label(__('resources.order_item.form.qty'))
            ->required()
            ->numeric();
    }

    protected static function notesInput(): Textarea
    {
        return Textarea::make('notes')
            ->label(__('resources.order_item.form.notes'))
            ->columnSpanFull();
    }
}
