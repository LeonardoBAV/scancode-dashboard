<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Events\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::nameEntry(),
                self::startEntry(),
                self::endEntry(),
                self::createdAtEntry(),
                self::updatedAtEntry(),
            ]);
    }

    protected static function nameEntry(): TextEntry
    {
        return TextEntry::make('name')
            ->label(__('resources.event.infolist.name'))
            ->columnSpanFull();
    }

    protected static function startEntry(): TextEntry
    {
        return TextEntry::make('start')
            ->label(__('resources.event.infolist.start'))
            ->date('d/m/Y');
    }

    protected static function endEntry(): TextEntry
    {
        return TextEntry::make('end')
            ->label(__('resources.event.infolist.end'))
            ->date('d/m/Y');
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
