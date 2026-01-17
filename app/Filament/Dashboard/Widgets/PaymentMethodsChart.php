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
        
        // Paleta variada com tons suaves (não muito luminosos)
        $colors = [
            'rgb(99, 102, 241)',   // Indigo suave (primary)
            'rgb(34, 197, 94)',    // Verde suave (green-500)
            'rgb(251, 146, 60)',   // Laranja suave (orange-400)
            'rgb(168, 85, 247)',   // Roxo suave (purple-500)
            'rgb(14, 165, 233)',   // Azul céu suave (sky-500)
            'rgb(236, 72, 153)',   // Rosa suave (pink-500)
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
                    'borderWidth' => 0,
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
