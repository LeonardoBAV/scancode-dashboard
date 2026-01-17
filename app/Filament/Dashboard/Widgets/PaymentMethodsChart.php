<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class PaymentMethodsChart extends ChartWidget
{
    protected static ?int $sort = 6;

    // protected ?string $maxHeight = '350px';

    // protected int|string|array $columnSpan = 'full';

    public function getColumns(): int|array
    {
        return 6;
    }

    public function getHeading(): ?string
    {
        return __('widgets.payment_methods.heading');
    }

    protected function getData(): array
    {
        $paymentMethods = Order::query()
            ->select('payment_methods.name')
            ->selectRaw('COUNT(orders.id) as count')
            ->selectRaw('SUM(order_items.price * order_items.qty) as total')
            ->join('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->groupBy('payment_methods.id', 'payment_methods.name')
            ->orderByDesc('total')
            ->get();

        $labels = [];
        $data = [];
        $colors = [
            'rgb(99, 102, 241)',   // Indigo
            'rgb(34, 197, 94)',    // Green
            'rgb(251, 191, 36)',   // Yellow
            'rgb(239, 68, 68)',    // Red
            'rgb(168, 85, 247)',   // Purple
            'rgb(236, 72, 153)',   // Pink
        ];

        foreach ($paymentMethods as $index => $method) {
            $labels[] = $method->name.' (R$ '.number_format((int) $method->total, 2, ',', '.').')';
            $data[] = $method->total;
        }

        return [
            'datasets' => [
                [
                    'label' => __('widgets.payment_methods.label'),
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'maintainAspectRatio' => true,
        ];
    }
}
