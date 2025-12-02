<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesRepresentativesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::cpfColumn(),
                self::nameColumn(),
                self::emailColumn(),
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

    protected static function cpfColumn(): TextColumn
    {
        return TextColumn::make('cpf')
            ->label(__('resources.sales_representative.table.cpf'))
            ->searchable();
    }

    protected static function nameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('resources.sales_representative.table.name'))
            ->searchable();
    }

    protected static function emailColumn(): TextColumn
    {
        return TextColumn::make('email')
            ->label(__('resources.sales_representative.table.email'))
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
