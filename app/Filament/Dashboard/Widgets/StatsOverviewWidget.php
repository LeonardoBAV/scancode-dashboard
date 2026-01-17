<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Widgets;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $totalValue = OrderItem::query()
            ->select(DB::raw('SUM(price * qty) as total'))
            ->value('total') ?? 0;

        $averageTicket = $totalOrders > 0 ? $totalValue / $totalOrders : 0;

        $pendingOrders = Order::where('status', OrderStatusEnum::PENDING)->count();

        return [
            Stat::make(__('widgets.stats_overview.total_orders'), number_format($totalOrders, 0, ',', '.'))
                ->description(__('widgets.stats_overview.total_orders_description'))
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart($this->getOrdersTrend()),

            Stat::make(__('widgets.stats_overview.total_value'), 'R$ '.number_format((int) $totalValue, 2, ',', '.'))
                ->description(__('widgets.stats_overview.total_value_description'))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart($this->getValueTrend()),

            Stat::make(__('widgets.stats_overview.average_ticket'), 'R$ '.number_format($averageTicket, 2, ',', '.'))
                ->description(__('widgets.stats_overview.average_ticket_description'))
                ->descriptionIcon('heroicon-m-calculator')
                ->color('info'),

            Stat::make(__('widgets.stats_overview.pending_orders'), number_format($pendingOrders, 0, ',', '.'))
                ->description(__('widgets.stats_overview.pending_orders_description'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 10 ? 'warning' : 'gray'),
        ];
    }

    protected function getOrdersTrend(): array
    {
        return Order::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
    }

    protected function getValueTrend(): array
    {
        return OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(DB::raw('DATE(orders.created_at) as date'), DB::raw('SUM(order_items.price * order_items.qty) as total'))
            ->where('orders.created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total')
            ->toArray();
    }
}
