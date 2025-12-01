<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('resources.client.infolist.title'))
                    ->icon(Heroicon::OutlinedUsers)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        self::cpfCnpjEntry(),
                        self::corporateNameEntry(),
                        self::fantasyNameEntry(),
                        self::emailEntry(),
                        self::phoneEntry(),
                        Section::make(__('resources.client.infolist.timestamps_title'))
                            ->icon(Heroicon::OutlinedClock)
                            ->columns(2)
                            ->schema([
                                self::createdAtEntry(),
                                self::updatedAtEntry(),
                            ])->secondary()->columnSpanFull(),
                    ]),
            ]);
    }

    protected static function cpfCnpjEntry(): TextEntry
    {
        return TextEntry::make('cpf_cnpj')
            ->label(__('resources.client.infolist.cpf_cnpj'));
    }

    protected static function corporateNameEntry(): TextEntry
    {
        return TextEntry::make('corporate_name')
            ->label(__('resources.client.infolist.corporate_name'))
            ->placeholder('-');
    }

    protected static function fantasyNameEntry(): TextEntry
    {
        return TextEntry::make('fantasy_name')
            ->label(__('resources.client.infolist.fantasy_name'))
            ->placeholder('-');
    }

    protected static function emailEntry(): TextEntry
    {
        return TextEntry::make('email')
            ->label(__('resources.client.infolist.email'))
            ->placeholder('-');
    }

    protected static function phoneEntry(): TextEntry
    {
        return TextEntry::make('phone')
            ->label(__('resources.client.infolist.phone'))
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
