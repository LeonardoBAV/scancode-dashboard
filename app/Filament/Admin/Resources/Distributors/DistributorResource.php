<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Distributors;

use App\Filament\Admin\Resources\Distributors\Pages\ListDistributors;
use App\Filament\Admin\Resources\Distributors\Schemas\DistributorInfolist;
use App\Filament\Admin\Resources\Distributors\Tables\DistributorsTable;
use App\Models\Distributor;
use App\Traits\Filament\Resources\HasTranslatableLabels;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class DistributorResource extends Resource
{
    use HasTranslatableLabels;

    protected static ?string $model = Distributor::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-buildings';

    protected static ?string $recordTitleAttribute = 'name';

    protected static UnitEnum|string|null $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return DistributorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DistributorsTable::configure($table);
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
            'index' => ListDistributors::route('/'),
        ];
    }
}
