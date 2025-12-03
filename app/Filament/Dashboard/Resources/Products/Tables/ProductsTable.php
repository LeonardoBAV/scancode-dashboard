<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Products\Tables;

use App\Models\Product;
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
                self::nameColumn(),
                self::productCategoryColumn(),
                self::priceColumn(),
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

    protected static function nameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('resources.product.table.name'))
            ->description(fn (Product $record): string => $record->sku)
            ->searchable();
    }

    protected static function productCategoryColumn(): TextColumn
    {
        return TextColumn::make('productCategory.name')
            ->label(__('resources.product.table.product_category_name'))
            ->searchable();
    }

    protected static function priceColumn(): TextColumn
    {
        return TextColumn::make('price')
            ->label(__('resources.product.table.price'))
            ->money('BRL', locale: 'pt_BR')
            ->badge()
            ->color('primary')
            ->alignCenter()
            ->sortable();
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
