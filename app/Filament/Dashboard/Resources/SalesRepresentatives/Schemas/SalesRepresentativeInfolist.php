<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SalesRepresentativeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('resources.sales_representative.infolist.title'))
                    ->icon(Heroicon::OutlinedUserGroup)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        self::cpfEntry(),
                        self::nameEntry(),
                        self::emailEntry(),
                        Section::make(__('resources.sales_representative.infolist.timestamps_title'))
                            ->icon(Heroicon::OutlinedClock)
                            ->columns(2)
                            ->schema([
                                self::createdAtEntry(),
                                self::updatedAtEntry(),
                            ])->secondary()->columnSpanFull(),
                    ]),
            ]);
    }

    protected static function cpfEntry(): TextEntry
    {
        return TextEntry::make('cpf')->label(__('resources.sales_representative.infolist.cpf'));
    }

    protected static function nameEntry(): TextEntry
    {
        return TextEntry::make('name')->label(__('resources.sales_representative.infolist.name'));
    }

    protected static function emailEntry(): TextEntry
    {
        return TextEntry::make('email')->label(__('resources.sales_representative.infolist.email'));
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
