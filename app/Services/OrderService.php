<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $status = OrderStatusEnum::from($data['status']);

            $order = Order::create(
                Arr::except($data, ['status', 'order_items']),
            );

            $this->syncOrderItemsIfPresent($order, $data);
            $this->applyFinalStatus($order, $status);

            return $order->refresh()->load('orderItems');
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Order $order, array $data): Order
    {
        return DB::transaction(function () use ($order, $data): Order {
            $order->toPending();

            $status = OrderStatusEnum::from($data['status']);

            $order->update(
                Arr::except($data, ['status', 'order_items']),
            );

            $this->syncOrderItemsIfPresent($order, $data);
            $this->applyFinalStatus($order, $status);

            return $order->refresh()->load('orderItems');
        });
    }

    /**
     * Sync line items by product_id for the given order.
     *
     * @param  array<int, array<string, mixed>>  $orderItems
     */
    public function updateOrderItems(Order $order, array $orderItems): void
    {
        $distributorId = $order->distributor_id;
        $incomingByProductId = collect($orderItems)->keyBy('product_id');
        $incomingProductIds = $incomingByProductId->keys();

        $itemsQuery = $order->orderItems();

        if ($incomingProductIds->isEmpty()) {
            $itemsQuery->delete();
        } else {
            $itemsQuery->whereNotIn('product_id', $incomingProductIds)->delete();
        }

        foreach ($incomingByProductId as $productId => $item) {
            OrderItem::query()->updateOrCreate(
                [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                ],
                [
                    'distributor_id' => $distributorId,
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'notes' => $item['notes'] ?? null,
                ],
            );
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function syncOrderItemsIfPresent(Order $order, array $data): void
    {
        if (array_key_exists('order_items', $data)) {
            $this->updateOrderItems($order, $data['order_items']);
        }
    }

    private function applyFinalStatus(Order $order, OrderStatusEnum $status): void
    {
        match ($status) {
            OrderStatusEnum::COMPLETED => $order->toComplete(),
            OrderStatusEnum::CANCELLED => $order->toCancel(),
            default => null,
        };
    }
}
