<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Event;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Builder;

dataset('order_protected_columns', [
    'protected_columns' => [
        [
            'id',
            'created_at',
            'updated_at',
            'client_cpf_cnpj',
            'client_corporate_name',
            'client_fantasy_name',
            'payment_method_name',
        ],
    ],
]);

dataset('order_make_five', [
    fn () => Order::factory()->make(['distributor_id' => null]),
    fn () => Order::factory()->make(['distributor_id' => null]),
    fn () => Order::factory()->make(['distributor_id' => null]),
    fn () => Order::factory()->make(['distributor_id' => null]),
    fn () => Order::factory()->make(['distributor_id' => null]),
]);

dataset('order_validations', [
    'required' => [
        fn () => Order::factory()->make([
            'distributor_id' => null,
            'client_id' => null,
            'sales_representative_id' => null,
            'payment_method_id' => null,
            'event_id' => null,
        ]),
        'errors' => [
            'client_id' => 'required',
            'sales_representative_id' => 'required',
            'payment_method_id' => 'required',
            'event_id' => 'required',
        ],
    ],
]);

dataset('order_searchable_columns', [
    'by client fantasy name' => [
        fn (): Order => Order::whereNotNull('client_fantasy_name')->firstOrFail(),
        fn (string $searchValue): Order => Order::where('client_fantasy_name', '!=', $searchValue)->orWhereNull('client_fantasy_name')->firstOrFail(),
        fn (Order $order): string => $order->client_fantasy_name ?? throw new UnexpectedValueException('Client fantasy name not found'),
    ],
    'by sales representative name' => [
        fn (): Order => Order::whereHas('salesRepresentative', fn (Builder $q) => $q->whereNotNull('name'))->firstOrFail(),
        fn (string $searchValue): Order => Order::whereHas('salesRepresentative', fn (Builder $q) => $q->where('name', '!=', $searchValue))->firstOrFail(),
        fn (Order $order): string => $order->salesRepresentative->name ?? throw new UnexpectedValueException('Sales representative not found'),
    ],
    'by payment method name' => [
        fn (): Order => Order::whereNotNull('payment_method_name')->firstOrFail(),
        fn (string $searchValue): Order => Order::where('payment_method_name', '!=', $searchValue)->orWhereNull('payment_method_name')->firstOrFail(),
        fn (Order $order): string => $order->payment_method_name ?? throw new UnexpectedValueException('Payment method name not found'),
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
        'distributor_id' => $order->distributor_id,
        'status' => $order->status,
        'notes' => "{$order->notes} test",
        'event_id' => Event::factory()->for($order->distributor),
        'client_id' => Client::factory()->for($order->distributor),
        'sales_representative_id' => SalesRepresentative::factory()->for($order->distributor),
        'payment_method_id' => PaymentMethod::factory()->for($order->distributor),
    ]),
]);
