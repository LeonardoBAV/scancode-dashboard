<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Distributors\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DistributorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::nameColumn(),
                self::slugColumn(),
                self::isActiveColumn(),
                self::createdAtColumn(),
                self::updatedAtColumn(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->slideOver(),
            ]);
    }

    protected static function nameColumn(): TextColumn
    {
        return TextColumn::make('name')
            ->label(__('resources.distributor.table.name'))
            ->searchable()
            ->sortable();
    }

    protected static function slugColumn(): TextColumn
    {
        return TextColumn::make('slug')
            ->label(__('resources.distributor.table.slug'))
            ->searchable()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    protected static function isActiveColumn(): ToggleColumn
    {
        return ToggleColumn::make('is_active')
            ->label(__('resources.distributor.table.is_active'))
            ->sortable();
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
