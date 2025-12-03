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
                self::nameInput(),
                self::skuInput(),
                self::productCategoryInput(),
                self::priceInput(),
                self::barcodeInput(),
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

    protected static function productCategoryInput(): Select
    {
        return Select::make('product_category_id')
            ->label(__('resources.product.form.product_category_name'))
            ->searchable()
            ->preload()
            ->relationship('productCategory', 'name')
            ->createOptionForm([
                self::productCategoryNameInput(),
            ])
            ->editOptionForm([
                self::productCategoryNameInput(),
            ]);
    }

    protected static function productCategoryNameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('resources.product.form.product_category_name'))
            ->required();
    }
}
