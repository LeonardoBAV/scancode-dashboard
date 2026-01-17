<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Models\Client;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopClientsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    public function getColumns(): int|array
    {
        return 6;
    }

    public function getTableHeading(): ?string
    {
        return __('widgets.top_clients.heading');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Client::query()
                    ->select('clients.*')
                    ->selectRaw('COUNT(DISTINCT orders.id) as orders_count')
                    ->selectRaw('COALESCE(SUM(order_items.price * order_items.qty), 0) as total_value')
                    ->leftJoin('orders', 'clients.id', '=', 'orders.client_id')
                    ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
                    ->groupBy('clients.id')
                    ->orderByDesc('total_value')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('position')
                    ->label(__('widgets.top_clients.position'))
                    ->state(function (Client $record, $rowLoop): string {
                        return "#{$rowLoop->iteration}";
                    })
                    ->alignCenter()
                    ->grow(false),

                TextColumn::make('fantasy_name')
                    ->label(__('widgets.top_clients.name'))
                    ->searchable()
                    ->weight('medium')
                    ->description(fn (Client $record): string => $record->corporate_name)
                    ->default(fn (Client $record): string => $record->corporate_name),

                TextColumn::make('cpf_cnpj')
                    ->label(__('widgets.top_clients.document'))
                    ->toggleable(),

                TextColumn::make('orders_count')
                    ->label(__('widgets.top_clients.orders'))
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('total_value')
                    ->label(__('widgets.top_clients.total'))
                    ->money('BRL')
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd(),
            ])
            ->paginated(false);
    }
}
