<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\SalesRepresentatives;

use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\CreateSalesRepresentative;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\EditSalesRepresentative;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\ListSalesRepresentatives;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\ViewSalesRepresentative;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Schemas\SalesRepresentativeForm;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Schemas\SalesRepresentativeInfolist;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Tables\SalesRepresentativesTable;
use App\Models\SalesRepresentative;
use App\Traits\Filament\Resources\HasTranslatableLabels;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalesRepresentativeResource extends Resource
{
    use HasTranslatableLabels;

    protected static ?string $model = SalesRepresentative::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return SalesRepresentativeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SalesRepresentativeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesRepresentativesTable::configure($table);
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
            'index' => ListSalesRepresentatives::route('/'),
            'create' => CreateSalesRepresentative::route('/create'),
            'view' => ViewSalesRepresentative::route('/{record}'),
            'edit' => EditSalesRepresentative::route('/{record}/edit'),
        ];
    }
}
