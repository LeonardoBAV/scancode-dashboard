<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::cpfCnpjColumn(),
                self::corporateNameColumn(),
                self::fantasyNameColumn(),
                self::emailColumn(),
                self::phoneColumn(),
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

    protected static function cpfCnpjColumn(): TextColumn
    {
        return TextColumn::make('cpf_cnpj')
            ->label(__('resources.client.table.cpf_cnpj'))
            ->searchable();
    }

    protected static function corporateNameColumn(): TextColumn
    {
        return TextColumn::make('corporate_name')
            ->label(__('resources.client.table.corporate_name'))
            ->searchable();
    }

    protected static function fantasyNameColumn(): TextColumn
    {
        return TextColumn::make('fantasy_name')
            ->label(__('resources.client.table.fantasy_name'))
            ->searchable();
    }

    protected static function emailColumn(): TextColumn
    {
        return TextColumn::make('email')
            ->label(__('resources.client.table.email'))
            ->searchable();
    }

    protected static function phoneColumn(): TextColumn
    {
        return TextColumn::make('phone')
            ->label(__('resources.client.table.phone'))
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
