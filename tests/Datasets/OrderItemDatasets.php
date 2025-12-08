<?php

declare(strict_types=1);

use App\Models\OrderItem;

dataset('order_item_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('order_item_updated', [
    fn (OrderItem $orderItem) => OrderItem::factory()->make([
        'price' => $orderItem->price + 10,
        'qty' => $orderItem->qty + 1,
        'notes' => "{$orderItem->notes} test",
    ]),
]);
