<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesByHourChart extends ChartWidget
{
    public ?string $filter = 'today';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('widgets.sales_by_hour.heading');
    }

    protected function getData(): array
    {
        $query = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                DB::raw('HOUR(orders.created_at) as hour'),
                DB::raw('SUM(order_items.price * order_items.qty) as total')
            );

        // Aplicar filtro apenas se for "today"
        if ($this->filter === 'today') {
            $query->whereDate('orders.created_at', today());
        }

        $salesByHour = $query
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour')
            ->toArray();

        // Preencher horas vazias com 0
        $hours = range(0, 23);
        $data = [];
        $labels = [];

        foreach ($hours as $hour) {
            $data[] = $salesByHour[$hour] ?? 0;
            $labels[] = sprintf('%02d:00', $hour);
        }

        return [
            'datasets' => [
                [
                    'label' => __('widgets.sales_by_hour.label'),
                    'data' => $data,
                    'borderColor' => 'rgb(99, 102, 241)',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => __('widgets.sales_by_hour.filters.today'),
            'all' => __('widgets.sales_by_hour.filters.all_time'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "R$ " + value.toFixed(2); }',
                    ],
                ],
            ],
        ];
    }
}
