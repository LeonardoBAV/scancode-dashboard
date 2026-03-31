<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::statusEntry(),
                self::eventEntry(),
                self::clientEntry(),
                self::salesRepresentativeEntry(),
                self::paymentMethodEntry(),
                self::notesEntry(),
                self::createdAtEntry(),
                self::updatedAtEntry(),
            ]);
    }

    protected static function statusEntry(): TextEntry
    {
        return TextEntry::make('status')
            ->label(__('resources.order.infolist.status'))
            ->state(fn (Order $record): string => $record->status->label())
            ->badge()
            ->color(fn (Order $record): string => $record->status->color());
    }

    protected static function eventEntry(): TextEntry
    {
        return TextEntry::make('event.name')
            ->label(__('resources.order.infolist.event'));
    }

    protected static function clientEntry(): TextEntry
    {
        return TextEntry::make('client.fantasy_name')
            ->label(__('resources.order.infolist.client'));
    }

    protected static function salesRepresentativeEntry(): TextEntry
    {
        return TextEntry::make('salesRepresentative.name')
            ->label(__('resources.order.infolist.sales_representative'));
    }

    protected static function paymentMethodEntry(): TextEntry
    {
        return TextEntry::make('paymentMethod.name')
            ->label(__('resources.order.infolist.payment_method'))
            ->placeholder('-');
    }

    protected static function notesEntry(): TextEntry
    {
        return TextEntry::make('notes')
            ->label(__('resources.order.infolist.notes'))
            ->placeholder('-')
            ->columnSpanFull();
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
