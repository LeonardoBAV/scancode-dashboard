<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Files\Tables;

use App\Models\File;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                self::descriptionColumn(),
                self::pathColumn(),
                self::fileTypeColumn(),
                self::createdAtColumn(),
                self::updatedAtColumn(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function descriptionColumn(): TextColumn
    {
        return TextColumn::make('description')
            ->label(__('resources.file.table.description'))
            ->searchable()
            ->sortable()
            ->placeholder('-');
    }

    protected static function pathColumn(): TextColumn
    {
        return TextColumn::make('path')
            ->label(__('resources.file.table.path'))
            ->formatStateUsing(fn (string $state): string => basename($state))
            ->description(fn (File $record): string => $record->path)
            ->searchable()
            ->sortable();
    }

    protected static function fileTypeColumn(): TextColumn
    {
        return TextColumn::make('fileType.name')
            ->label(__('resources.file.table.file_type_name'))
            ->searchable()
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
