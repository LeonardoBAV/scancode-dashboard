<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Events\Schemas;

use App\Models\Event;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::nameInput(),
                self::startInput(),
                self::endInput(),
                self::hasStockLimitInput(),
            ]);
    }

    protected static function nameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('resources.event.form.name'))
            ->required()
            ->maxLength(255)
            ->scopedUnique(Event::class, 'name', ignoreRecord: true);
    }

    protected static function startInput(): DatePicker
    {
        return DatePicker::make('start')
            ->label(__('resources.event.form.start'))
            ->required()
            ->native(false)
            ->displayFormat('d/m/Y')
            ->live();
    }

    protected static function endInput(): DatePicker
    {
        return DatePicker::make('end')
            ->label(__('resources.event.form.end'))
            ->required()
            ->native(false)
            ->displayFormat('d/m/Y')
            ->afterOrEqual(fn (Get $get): ?string => $get('start'));
    }

    protected static function hasStockLimitInput(): Toggle
    {
        return Toggle::make('has_stock_limit')
            ->label(__('resources.event.form.has_stock_limit'))
            ->default(false);
    }
}
