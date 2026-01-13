<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Orders;

use App\Filament\Dashboard\Resources\Orders\Pages\ListOrders;
use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Filament\Dashboard\Resources\Orders\RelationManagers\OrderItemsRelationManager;
use App\Filament\Dashboard\Resources\Orders\Schemas\OrderForm;
use App\Filament\Dashboard\Resources\Orders\Schemas\OrderInfolist;
use App\Filament\Dashboard\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use App\Traits\Filament\Resources\HasTranslatableLabels;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    use HasTranslatableLabels;

    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-clipboard-text';

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
            OrderItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }
}
