<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::skuInput(),
                self::barcodeInput(),
                self::nameInput(),
                self::priceInput(),
                self::productCategoryIdInput(),
            ]);
    }

    protected static function skuInput(): TextInput
    {
        return TextInput::make('sku')
            ->label(__('resources.product.form.sku'))
            ->required();
    }

    protected static function barcodeInput(): TextInput
    {
        return TextInput::make('barcode')
            ->label(__('resources.product.form.barcode'))
            ->required()
            ->unique(ignoreRecord: true);
    }

    protected static function nameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('resources.product.form.name'))
            ->required();
    }

    protected static function priceInput(): TextInput
    {
        return TextInput::make('price')
            ->label(__('resources.product.form.price'))
            ->required()
            ->numeric()
            ->prefix('$');
    }

    protected static function productCategoryIdInput(): Select
    {
        return Select::make('product_category_id')
            ->label(__('resources.product.form.product_category_id'))
            ->relationship('productCategory', 'id')
            ->required();
    }
}
