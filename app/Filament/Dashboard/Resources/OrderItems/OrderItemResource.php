<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\OrderItems;

use App\Filament\Dashboard\Resources\OrderItems\Pages\CreateOrderItem;
use App\Filament\Dashboard\Resources\OrderItems\Pages\EditOrderItem;
use App\Filament\Dashboard\Resources\OrderItems\Pages\ListOrderItems;
use App\Filament\Dashboard\Resources\OrderItems\Pages\ViewOrderItem;
use App\Filament\Dashboard\Resources\OrderItems\Schemas\OrderItemForm;
use App\Filament\Dashboard\Resources\OrderItems\Schemas\OrderItemInfolist;
use App\Filament\Dashboard\Resources\OrderItems\Tables\OrderItemsTable;
use App\Models\OrderItem;
use App\Traits\Filament\Resources\HasTranslatableLabels;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrderItemResource extends Resource
{
    use HasTranslatableLabels;

    protected static ?string $model = OrderItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'product.name';

    public static function form(Schema $schema): Schema
    {
        return OrderItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderItemsTable::configure($table);
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
            /*'index' => ListOrderItems::route('/'),
            'create' => CreateOrderItem::route('/create'),
            'view' => ViewOrderItem::route('/{record}'),
            'edit' => EditOrderItem::route('/{record}/edit'),*/
        ];
    }
}
