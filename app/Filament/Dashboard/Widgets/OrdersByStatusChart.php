<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersByStatusChart extends ChartWidget
{
    // protected static ?string $heading = null;

    protected static ?int $sort = 6;

    // protected ?string $maxHeight = '350px';

    // protected int|string|array $columnSpan = 'full';

    public function getColumns(): int|array
    {
        return 6;
    }

    public function getHeading(): ?string
    {
        return __('widgets.orders_by_status.heading');
    }

    protected function getData(): array
    {
        $pending = Order::where('status', OrderStatusEnum::PENDING)->count();
        $completed = Order::where('status', OrderStatusEnum::COMPLETED)->count();
        $cancelled = Order::where('status', OrderStatusEnum::CANCELLED)->count();

        return [
            'datasets' => [
                [
                    'label' => __('widgets.orders_by_status.label'),
                    'data' => [$pending, $completed, $cancelled],
                    'backgroundColor' => [
                        'rgb(251, 191, 36)',  // warning (pending)
                        'rgb(99, 102, 241)',  // primary (completed)
                        'rgb(239, 68, 68)',   // danger (cancelled)
                    ],
                ],
            ],
            'labels' => [
                __('widgets.orders_by_status.pending')." ({$pending})",
                __('widgets.orders_by_status.completed')." ({$completed})",
                __('widgets.orders_by_status.cancelled')." ({$cancelled})",
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
