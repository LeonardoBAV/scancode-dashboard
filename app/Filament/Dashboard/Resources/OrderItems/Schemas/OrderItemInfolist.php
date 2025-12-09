<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\OrderItems\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::productNameEntry(),
                self::priceEntry(),
                self::qtyEntry(),
                self::totalEntry(),
                self::notesEntry(),
                self::createdAtEntry(),
                self::updatedAtEntry(),
            ]);
    }

    protected static function productNameEntry(): TextEntry
    {
        return TextEntry::make('product.name')
            ->label(__('resources.order_item.infolist.product_name'));
    }

    protected static function priceEntry(): TextEntry
    {
        return TextEntry::make('price')
            ->label(__('resources.order_item.infolist.price'))
            ->money('BRL', locale: 'pt_BR');
    }

    protected static function qtyEntry(): TextEntry
    {
        return TextEntry::make('qty')
            ->label(__('resources.order_item.infolist.qty'))
            ->numeric();
    }

    protected static function totalEntry(): TextEntry
    {
        return TextEntry::make('price * qty')
            ->label(__('resources.order_item.infolist.total'))
            ->money('BRL', locale: 'pt_BR');
    }

    protected static function notesEntry(): TextEntry
    {
        return TextEntry::make('notes')
            ->label(__('resources.order_item.infolist.notes'))
            ->placeholder('-');
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
