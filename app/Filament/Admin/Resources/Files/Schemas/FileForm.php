<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Files\Schemas;

use App\Enums\FileTypeEnum;
use App\Models\File;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class FileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::pathInput(),
                self::descriptionInput(),
                self::typeInput(),
            ]);
    }

    protected static function pathInput(): FileUpload
    {
        return FileUpload::make('path')
            ->label(__('resources.file.form.path'))
            ->helperText(__('resources.file.form.path_helper'))
            ->uploadingMessage(__('resources.file.form.path_uploading'))
            ->required()
            ->disk(File::DISK)
            ->maxSize(51_200)
            ->downloadable()
            ->openable()
            ->columnSpanFull();
    }

    protected static function descriptionInput(): Textarea
    {
        return Textarea::make('description')
            ->label(__('resources.file.form.description'))
            ->rows(3)
            ->columnSpanFull();
    }

    protected static function typeInput(): Select
    {
        return Select::make('type')
            ->label(__('resources.file.form.type'))
            ->options(collect(FileTypeEnum::cases())->mapWithKeys(
                fn (FileTypeEnum $type): array => [$type->value => $type->label()],
            ))
            ->required()
            ->native(false);
    }
}
