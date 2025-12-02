<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Products\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::skuEntry(),
                self::barcodeEntry(),
                self::nameEntry(),
                self::priceEntry(),
                self::productCategoryIdEntry(),
                self::createdAtEntry(),
                self::updatedAtEntry(),
            ]);
    }

    protected static function skuEntry(): TextEntry
    {
        return TextEntry::make('sku')
            ->label(__('resources.product.infolist.sku'));
    }

    protected static function barcodeEntry(): TextEntry
    {
        return TextEntry::make('barcode')
            ->label(__('resources.product.infolist.barcode'));
    }

    protected static function nameEntry(): TextEntry
    {
        return TextEntry::make('name')
            ->label(__('resources.product.infolist.name'));
    }

    protected static function priceEntry(): TextEntry
    {
        return TextEntry::make('price')
            ->label(__('resources.product.infolist.price'))
            ->money();
    }

    protected static function productCategoryIdEntry(): TextEntry
    {
        return TextEntry::make('productCategory.name')
            ->label(__('resources.product.infolist.product_category_id'));
    }

    protected static function createdAtEntry(): TextEntry
    {
        return TextEntry::make('created_at')
            ->translateLabel()
            ->dateTime('d/m/Y H:i:s')
            ->timezone('America/Sao_Paulo')
            ->placeholder('-');
    }

    protected static function updatedAtEntry(): TextEntry
    {
        return TextEntry::make('updated_at')
            ->translateLabel()
            ->dateTime('d/m/Y H:i:s')
            ->timezone('America/Sao_Paulo')
            ->placeholder('-');
    }
}
