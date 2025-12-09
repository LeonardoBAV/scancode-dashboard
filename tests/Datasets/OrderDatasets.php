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
        fn (): Order => Order::whereHas('client', fn (Builder $q) => $q->whereNotNull('fantasy_name'))->firstOrFail(),
        fn (string $searchValue): Order => Order::whereHas('client', fn (Builder $q) => $q->where('fantasy_name', '!=', $searchValue))->firstOrFail(),
        fn (Order $order): string => $order->client->fantasy_name ?? throw new UnexpectedValueException('Client not found'),
    ],
    'by sales representative name' => [
        fn (): Order => Order::whereHas('salesRepresentative', fn (Builder $q) => $q->whereNotNull('name'))->firstOrFail(),
        fn (string $searchValue): Order => Order::whereHas('salesRepresentative', fn (Builder $q) => $q->where('name', '!=', $searchValue))->firstOrFail(),
        fn (Order $order): string => $order->salesRepresentative->name ?? throw new UnexpectedValueException('Sales representative not found'),
    ],
    'by payment method name' => [
        fn (): Order => Order::whereHas('paymentMethod', fn (Builder $q) => $q->whereNotNull('name'))->firstOrFail(),
        fn (string $searchValue): Order => Order::whereHas('paymentMethod', fn (Builder $q) => $q->where('name', '!=', $searchValue))->firstOrFail(),
        fn (Order $order): string => $order->paymentMethod->name ?? throw new UnexpectedValueException('Payment method not found'),
    ],
    'by status' => [
        fn (): Order => Order::whereNotNull('status')->firstOrFail(),
        fn (string $searchValue): Order => Order::where('status', '!=', $searchValue)->firstOrFail(),
        fn (Order $order): string => $order->status->value,
    ],
]);

dataset('order_sortable_columns', [
    'by status' => 'status',
    'by created at' => 'created_at',
    'by updated at' => 'updated_at',
]);

dataset('order_updated', [
    fn (Order $order): Order => Order::factory()->make([
        'status' => ($order->status === OrderStatusEnum::PENDING) ? OrderStatusEnum::COMPLETED : OrderStatusEnum::PENDING,
        'notes' => "{$order->notes} test",
    ]),
]);
