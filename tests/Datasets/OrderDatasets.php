<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

dataset('order_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('order_make_five', [
    fn () => Order::factory()->make(),
    fn () => Order::factory()->make(),
    fn () => Order::factory()->make(),
    fn () => Order::factory()->make(),
    fn () => Order::factory()->make(),
]);

dataset('order_validations', [
    'required' => [
        fn () => Order::factory()->make([
            'status' => null,
            'client_id' => null,
            'sales_representative_id' => null,
            'payment_method_id' => null,
        ]),
        'errors' => [
            'status' => 'required',
            'client_id' => 'required',
            'sales_representative_id' => 'required',
            'payment_method_id' => 'required',
        ],
    ],
]);

dataset('order_searchable_columns', [
    'by client fantasy name' => [
        fn () => Order::whereHas('client', fn (Builder $q) => $q->whereNotNull('fantasy_name'))->first(),
        fn (string $searchValue) => Order::whereHas('client', fn (Builder $q) => $q->where('fantasy_name', '!=', $searchValue))->first(),
        fn (Order $order) => $order->client->fantasy_name,
    ],
    'by sales representative name' => [
        fn () => Order::whereHas('salesRepresentative', fn (Builder $q) => $q->whereNotNull('name'))->first(),
        fn (string $searchValue) => Order::whereHas('salesRepresentative', fn (Builder $q) => $q->where('name', '!=', $searchValue))->first(),
        fn (Order $order) => $order->salesRepresentative->name,
    ],
    'by payment method name' => [
        fn () => Order::whereHas('paymentMethod', fn (Builder $q) => $q->whereNotNull('name'))->first(),
        fn (string $searchValue) => Order::whereHas('paymentMethod', fn (Builder $q) => $q->where('name', '!=', $searchValue))->first(),
        fn (Order $order) => $order->paymentMethod->name,
    ],
    'by status' => [
        fn () => Order::whereNotNull('status')->first(),
        fn (string $searchValue) => Order::where('status', '!=', $searchValue)->first(),
        fn (Order $order) => $order->status->value,
    ],
]);

dataset('order_sortable_columns', [
    'by status' => 'status',
]);

dataset('order_updated', [
    fn (Order $order) => Order::factory()->make([
        'status' => ($order->status === OrderStatusEnum::PENDING) ? OrderStatusEnum::COMPLETED : OrderStatusEnum::PENDING,
        'notes' => "{$order->notes} test",
    ]),
]);
