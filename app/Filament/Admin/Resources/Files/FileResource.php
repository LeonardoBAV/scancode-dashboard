<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Files;

use App\Filament\Admin\Resources\Files\Pages\CreateFile;
use App\Filament\Admin\Resources\Files\Pages\EditFile;
use App\Filament\Admin\Resources\Files\Pages\ListFiles;
use App\Filament\Admin\Resources\Files\Pages\ViewFile;
use App\Filament\Admin\Resources\Files\Schemas\FileForm;
use App\Filament\Admin\Resources\Files\Schemas\FileInfolist;
use App\Filament\Admin\Resources\Files\Tables\FilesTable;
use App\Models\File;
use App\Traits\Filament\Resources\HasTranslatableLabels;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FileResource extends Resource
{
    use HasTranslatableLabels;

    protected static ?string $model = File::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-file';

    protected static ?string $recordTitleAttribute = 'description';

    protected static UnitEnum|string|null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return FileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFiles::route('/'),
            'create' => CreateFile::route('/create'),
            'view' => ViewFile::route('/{record}'),
            'edit' => EditFile::route('/{record}/edit'),
        ];
    }
}
