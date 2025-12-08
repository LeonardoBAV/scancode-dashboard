<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Orders;

use App\Filament\Dashboard\Resources\Orders\Pages\CreateOrder;
use App\Filament\Dashboard\Resources\Orders\Pages\EditOrder;
use App\Filament\Dashboard\Resources\Orders\Pages\ListOrders;
use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Filament\Dashboard\Resources\Orders\Schemas\OrderForm;
use App\Filament\Dashboard\Resources\Orders\Schemas\OrderInfolist;
use App\Filament\Dashboard\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use App\Traits\Filament\Resources\HasTranslatableLabels;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    use HasTranslatableLabels;

    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return OrderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
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
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'view' => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
