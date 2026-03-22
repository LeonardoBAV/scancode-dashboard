<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopProductsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    // protected int|string|array $columnSpan = 'full';

    public function getColumns(): int|array
    {
        return 6;
    }

    public function getTableHeading(): ?string
    {
        return __('widgets.top_products.heading');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->select('products.*')
                    ->selectRaw('COALESCE(SUM(order_items.qty), 0) as total_quantity')
                    ->selectRaw('COALESCE(SUM(order_items.price * order_items.qty), 0) as total_value')
                    ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
                    ->groupBy('products.id')
                    ->orderByDesc('total_quantity')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('position')
                    ->label(__('widgets.top_products.position'))
                    ->state(function (Product $record, $rowLoop): string {
                        return "#{$rowLoop->iteration}";
                    })
                    ->alignCenter()
                    ->grow(false),

                TextColumn::make('name')
                    ->label(__('widgets.top_products.name'))
                    ->weight('medium')
                    ->description(fn (Product $record): string => $record->sku),

                TextColumn::make('productCategory.name')
                    ->label(__('widgets.top_products.category'))
                    ->badge()
                    ->color('info')
                    ->default('-'),

                TextColumn::make('total_quantity')
                    ->label(__('widgets.top_products.quantity'))
                    ->alignRight()
                    ->badge()
                    ->color('warning')
                    ->suffix(' un')
                    ->description(fn (Product $record): string => 'R$ '.number_format((int) $record->total_value, 2, ',', '.'))
                    ->weight('medium'),
            ])
            ->paginated(false);
    }
}
