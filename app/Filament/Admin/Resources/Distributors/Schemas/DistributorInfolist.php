<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Distributors\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DistributorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::nameEntry(),
                self::slugEntry(),
                self::isActiveEntry(),
                self::createdAtEntry(),
                self::updatedAtEntry(),
            ]);
    }

    protected static function nameEntry(): TextEntry
    {
        return TextEntry::make('name')
            ->label(__('resources.distributor.infolist.name'))
            ->columnSpanFull();
    }

    protected static function slugEntry(): TextEntry
    {
        return TextEntry::make('slug')
            ->label(__('resources.distributor.infolist.slug'));
    }

    protected static function isActiveEntry(): IconEntry
    {
        return IconEntry::make('is_active')
            ->label(__('resources.distributor.infolist.is_active'))
            ->boolean();
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
