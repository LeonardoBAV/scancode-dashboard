<?php

declare(strict_types=1);

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder;

dataset('order_item_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('order_item_searchable_columns', [
    'by product name' => [
        fn (): OrderItem => OrderItem::whereHas('product', fn (Builder $q) => $q->whereNotNull('name'))->firstOrFail(),
        fn (string $searchValue): OrderItem => OrderItem::whereHas('product', fn (Builder $q) => $q->where('name', '!=', $searchValue))->firstOrFail(),
        fn (OrderItem $orderItem): string => $orderItem->product->name ?? throw new UnexpectedValueException('Product not found'),
    ],
]);

dataset('order_item_sortable_columns', [
    'by price' => 'price',
    'by qty' => 'qty',
    'by created at' => 'created_at',
    'by updated at' => 'updated_at',
]);

dataset('order_item_make_five_order_items', [
    fn (): OrderItem => OrderItem::factory()->make(['distributor_id' => null]),
    fn (): OrderItem => OrderItem::factory()->make(['distributor_id' => null]),
    fn (): OrderItem => OrderItem::factory()->make(['distributor_id' => null]),
    fn (): OrderItem => OrderItem::factory()->make(['distributor_id' => null]),
    fn (): OrderItem => OrderItem::factory()->make(['distributor_id' => null]),
]);

dataset('order_item_updated', [
    fn (OrderItem $orderItem): OrderItem => OrderItem::factory()->make([
        'distributor_id' => $orderItem->distributor_id,
        'order_id' => $orderItem->order_id,
        'product_id' => $orderItem->product_id,
        'price' => $orderItem->price + 10,
        'qty' => $orderItem->qty + 1,
        'notes' => "{$orderItem->notes} test",
    ]),
]);

dataset('order_item_validations', [
    'required' => [
        [
            'product_id' => null,
            'price' => null,
            'qty' => null,
        ],
        'errors' => [
            'product_id' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ],
    ],
    'numeric' => [
        [
            'product_id' => 1,
            'price' => 'invalid-price',
            'qty' => 'invalid-qty',
        ],
        'errors' => [
            'price' => 'numeric',
            'qty' => 'numeric',
        ],
    ],
]);

/*dataset('order_validations', [
    'required' => [
        fn () => Order::factory()->make([
            'client_id' => null,
            'sales_representative_id' => null,
            'payment_method_id' => null,
        ]),
        'errors' => [
            'client_id' => 'required',
            'sales_representative_id' => 'required',
            'payment_method_id' => 'required',
        ],
    ],
]);*/

/*
//obs multi-tenancy: isso aqui verifica por que foi apagado
dataset('visibility_of_record_actions_by_order_status', [
    'when order is pending' => [
        function (): Order {
            $order = Order::factory(['status' => OrderStatusEnum::PENDING])
                ->has(OrderItem::factory()->count(1))
                ->create();

            return $order;
        },
        'expected' => [
            'view' => true,
            'edit' => true,
            'delete' => true,
        ],
    ],
    'when order is completed' => [
        function (): Order {
            $order = Order::factory(['status' => OrderStatusEnum::PENDING])
                ->has(OrderItem::factory()->count(1))
                ->create();
            $order->toComplete();

            return $order;
        },
        'expected' => [
            'view' => true,
            'edit' => false,
            'delete' => false,
        ],
    ],
    'when order is cancelled' => [
        function (): Order {
            $order = Order::factory(['status' => OrderStatusEnum::PENDING])
                ->has(OrderItem::factory()->count(1))
                ->create();
            $order->toCancel();

            return $order;
        },
        'expected' => [
            'view' => true,
            'edit' => false,
            'delete' => false,
        ],
    ],
]);

*/
