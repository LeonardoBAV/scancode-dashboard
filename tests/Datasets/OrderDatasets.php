<?php

declare(strict_types=1);

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
        fn (Order $order) => $order->client->fantasy_name,
        fn (string $searchValue) => Order::whereHas('client', function (Builder $query) use ($searchValue) {
            $query->where('fantasy_name', '!=', $searchValue);
        })->first(),
    ],
    'by sales representative name' => [
        fn (Order $order) => $order->salesRepresentative->name,
        fn (string $searchValue) => Order::whereHas('salesRepresentative', function (Builder $query) use ($searchValue) {
            $query->where('name', '!=', $searchValue);
        })->first(),
    ],
    'by payment method name' => [
        fn (Order $order) => $order->paymentMethod->name,
        fn (string $searchValue) => Order::whereHas('paymentMethod', function (Builder $query) use ($searchValue) {
            $query->where('name', '!=', $searchValue);
        })->first(),
    ],
    'by status' => [
        fn (Order $order) => $order->status->value,
        fn (string $searchValue) => Order::where('status', '!=', $searchValue)->first(),
    ],
]);

dataset('order_sortable_columns', [
    'by status' => 'status',
]);
