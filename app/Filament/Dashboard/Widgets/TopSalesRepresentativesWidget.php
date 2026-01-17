<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Models\SalesRepresentative;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopSalesRepresentativesWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): ?string
    {
        return __('widgets.top_sales_representatives.heading');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SalesRepresentative::query()
                    ->select('sales_representatives.*')
                    ->selectRaw('COUNT(DISTINCT orders.id) as orders_count')
                    ->selectRaw('COALESCE(SUM(order_items.price * order_items.qty), 0) as total_value')
                    ->leftJoin('orders', 'sales_representatives.id', '=', 'orders.sales_representative_id')
                    ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->groupBy('sales_representatives.id')
                    ->orderByDesc('total_value')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('position')
                    ->label(__('widgets.top_sales_representatives.position'))
                    ->state(function (SalesRepresentative $record, $rowLoop): string {
                        return "#{$rowLoop->iteration}";
                    })
                    ->alignCenter()
                    ->grow(false),

                TextColumn::make('name')
                    ->label(__('widgets.top_sales_representatives.name'))
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('orders_count')
                    ->label(__('widgets.top_sales_representatives.orders'))
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('total_value')
                    ->label(__('widgets.top_sales_representatives.total'))
                    ->money('BRL')
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
