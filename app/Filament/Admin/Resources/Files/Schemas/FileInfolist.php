<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Files\Schemas;

use App\Models\File;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::pathEntry(),
                self::descriptionEntry(),
                self::typeEntry(),
                self::createdAtEntry(),
                self::updatedAtEntry(),
            ]);
    }

    protected static function pathEntry(): TextEntry
    {
        return TextEntry::make('path')
            ->label(__('resources.file.infolist.path'))
            ->formatStateUsing(fn (File $record): string => basename($record->path))
            ->url(fn (File $record): ?string => $record->getPublicUrl())
            ->openUrlInNewTab()
            ->columnSpanFull();
    }

    protected static function descriptionEntry(): TextEntry
    {
        return TextEntry::make('description')
            ->label(__('resources.file.infolist.description'))
            ->placeholder('-')
            ->columnSpanFull();
    }

    protected static function typeEntry(): TextEntry
    {
        return TextEntry::make('type')
            ->label(__('resources.file.infolist.type'))
            ->formatStateUsing(fn (File $record): string => $record->type->label());
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
