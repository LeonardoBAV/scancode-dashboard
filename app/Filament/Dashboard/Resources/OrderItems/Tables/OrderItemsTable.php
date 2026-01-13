<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\OrderItems\Tables;

use App\Models\OrderItem;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class OrderItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::productNameColumn(),
                self::priceColumn(),
                self::qtyColumn(),
                self::totalColumn(),
                self::createdAtColumn(),
                self::updatedAtColumn(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()->slideOver()->visible(fn (OrderItem $record): bool => $record->canBeUpdated()),
                DeleteAction::make()->visible(fn (OrderItem $record): bool => $record->canBeDeleted()),
            ]);
    }

    protected static function productNameColumn(): TextColumn
    {
        return TextColumn::make('product.name')
            ->label(__('resources.order_item.table.product_name'))
            ->searchable();
    }

    protected static function priceColumn(): TextColumn
    {
        return TextColumn::make('price')
            ->label(__('resources.order_item.table.price'))
            ->money('BRL', locale: 'pt_BR')
            ->badge()
            ->color('primary')
            ->sortable();
    }

    protected static function qtyColumn(): TextColumn
    {
        return TextColumn::make('qty')
            ->label(__('resources.order_item.table.qty'))
            ->numeric()
            ->sortable()
            ->summarize(Sum::make()->label(__('resources.order_item.table.summarize.qty')));
    }

    protected static function totalColumn(): TextColumn
    {
        return TextColumn::make('total')
            ->label(__('resources.order_item.table.total'))
            ->money('BRL', locale: 'pt_BR')
            ->state(fn (OrderItem $record): string => strval($record->price * $record->qty))
            ->badge()
            ->color('success')
            ->default(0)
            ->summarize(Summarizer::make()->label(__('resources.order_item.table.summarize.total'))->using(fn (Builder $query): string => strval($query->sum(DB::raw('price * qty'))))->money('BRL', locale: 'pt_BR'));
    }

    protected static function createdAtColumn(): TextColumn
    {
        return TextColumn::make('created_at')
            ->label(__('resources.order_item.table.created_at'))
            ->dateTime('d/m/Y H:i:s')
            ->timezone('America/Sao_Paulo')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    protected static function updatedAtColumn(): TextColumn
    {
        return TextColumn::make('updated_at')
            ->label(__('resources.order_item.table.updated_at'))
            ->dateTime('d/m/Y H:i:s')
            ->timezone('America/Sao_Paulo')
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
