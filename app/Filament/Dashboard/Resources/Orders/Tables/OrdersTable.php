<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Orders\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::statusColumn(),
                self::clientColumn(),
                self::salesRepresentativeColumn(),
                self::paymentMethodColumn(),
                self::createdAtColumn(),
                self::updatedAtColumn(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    protected static function statusColumn(): TextColumn
    {
        return TextColumn::make('status')
            ->label(__('resources.order.table.status'))
            ->badge()
            ->searchable()
            ->sortable();
    }

    protected static function clientColumn(): TextColumn
    {
        return TextColumn::make('client.fantasy_name')
            ->label(__('resources.order.table.client'))
            ->searchable();
    }

    protected static function salesRepresentativeColumn(): TextColumn
    {
        return TextColumn::make('salesRepresentative.name')
            ->label(__('resources.order.table.sales_representative'))
            ->searchable();
    }

    protected static function paymentMethodColumn(): TextColumn
    {
        return TextColumn::make('paymentMethod.name')
            ->label(__('resources.order.table.payment_method'))
            ->placeholder(__('resources.order.table.payment_method_placeholder'))
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
