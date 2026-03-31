<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Events;

use App\Filament\Dashboard\Resources\Events\Pages\CreateEvent;
use App\Filament\Dashboard\Resources\Events\Pages\EditEvent;
use App\Filament\Dashboard\Resources\Events\Pages\ListEvents;
use App\Filament\Dashboard\Resources\Events\Pages\ViewEvent;
use App\Filament\Dashboard\Resources\Events\Schemas\EventForm;
use App\Filament\Dashboard\Resources\Events\Schemas\EventInfolist;
use App\Filament\Dashboard\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use App\Traits\Filament\Resources\HasTranslatableLabels;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EventResource extends Resource
{
    use HasTranslatableLabels;

    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-calendar';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'view' => ViewEvent::route('/{record}'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
