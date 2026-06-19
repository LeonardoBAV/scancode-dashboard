<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Files\Schemas;

use App\Models\FileType;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::pathInput(),
                self::descriptionInput(),
                self::fileTypeInput(),
            ]);
    }

    protected static function pathInput(): FileUpload
    {
        return FileUpload::make('path')
            ->label(__('resources.file.form.path'))
            ->helperText(__('resources.file.form.path_helper'))
            ->uploadingMessage(__('resources.file.form.path_uploading'))
            ->required()
            ->disk('public')
            ->directory('files')
            ->visibility('public')
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

    protected static function fileTypeInput(): Select
    {
        return Select::make('file_type_id')
            ->label(__('resources.file.form.file_type_name'))
            ->searchable()
            ->preload()
            ->required()
            ->relationship('fileType', 'name')
            ->createOptionAction(fn (Action $action): Action => $action->successNotificationTitle(__('filament-actions::create.single.notifications.created.title')))
            ->editOptionAction(fn (Action $action): Action => $action->successNotificationTitle(__('filament-actions::edit.single.notifications.saved.title')))
            ->createOptionForm([
                self::fileTypeNameInput(),
            ])
            ->editOptionForm([
                self::fileTypeNameInput(),
            ]);
    }

    protected static function fileTypeNameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('resources.file.form.file_type_name'))
            ->required()
            ->unique(FileType::class, 'name');
    }
}
