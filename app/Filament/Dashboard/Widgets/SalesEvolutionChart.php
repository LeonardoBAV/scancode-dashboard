<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesEvolutionChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = '7';

    public function getHeading(): ?string
    {
        return __('widgets.sales_evolution.heading');
    }

    protected function getData(): array
    {
        $days = (int) $this->filter;

        $salesByDay = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('SUM(order_items.price * order_items.qty) as total')
            )
            ->where('orders.created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $ordersData = [];
        $valueData = [];

        foreach ($salesByDay as $day) {
            $labels[] = \Carbon\Carbon::parse($day->date)->format('d/m');
            $ordersData[] = $day->orders_count;
            $valueData[] = $day->total;
        }

        return [
            'datasets' => [
                [
                    'label' => __('widgets.sales_evolution.orders'),
                    'data' => $ordersData,
                    'borderColor' => 'rgb(99, 102, 241)',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => __('widgets.sales_evolution.value'),
                    'data' => $valueData,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => __('widgets.sales_evolution.filters.7_days'),
            '15' => __('widgets.sales_evolution.filters.15_days'),
            '30' => __('widgets.sales_evolution.filters.30_days'),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => __('widgets.sales_evolution.orders'),
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => __('widgets.sales_evolution.value'),
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}
