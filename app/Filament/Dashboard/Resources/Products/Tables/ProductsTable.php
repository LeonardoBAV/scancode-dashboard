<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::skuColumn(),
                self::barcodeColumn(),
                self::nameColumn(),
                self::priceColumn(),
                self::productCategoryIdColumn(),
                self::createdAtColumn(),
                self::updatedAtColumn(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function skuColumn(): TextColumn
    {
        return TextColumn::make('sku')
            ->label(__('resources.product.table.sku'))
            ->searchable();
    }

    protected static function barcodeColumn(): TextColumn
    {
        return TextColumn::make('barcode')
            ->label(__('resources.product.table.barcode'))
            ->searchable();
    }

    protected static function nameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('resources.product.table.name'))
            ->searchable();
    }

    protected static function priceColumn(): TextColumn
    {
        return TextColumn::make('price')
            ->label(__('resources.product.table.price'))
            ->money()
            ->sortable();
    }

    protected static function productCategoryIdColumn(): TextColumn
    {
        return TextColumn::make('productCategory.name')
            ->label(__('resources.product.table.product_category_name'))
            ->searchable();
    }

    protected static function createdAtColumn(): TextColumn
    {
        return TextColumn::make('created_at')
            ->translateLabel()
            ->dateTime('d/m/Y H:i:s')
            ->timezone('America/Sao_Paulo')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    protected static function updatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->translateLabel()
            ->dateTime('d/m/Y H:i:s')
            ->timezone('America/Sao_Paulo')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
