<?php

declare(strict_types=1);

use App\Models\PaymentMethod;

dataset('payment_method_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('payment_method_make_five', [
    fn () => PaymentMethod::factory()->make(['distributor_id' => null]),
    fn () => PaymentMethod::factory()->make(['distributor_id' => null]),
    fn () => PaymentMethod::factory()->make(['distributor_id' => null]),
    fn () => PaymentMethod::factory()->make(['distributor_id' => null]),
    fn () => PaymentMethod::factory()->make(['distributor_id' => null]),
]);

dataset('payment_method_validations', [
    'required' => [
        fn () => PaymentMethod::factory()->make(['name' => null]),
        'errors' => ['name' => 'required'],
    ],
]);

dataset('payment_method_searchable_columns', [
    'by name' => [
        fn (): PaymentMethod => PaymentMethod::whereNotNull('name')->firstOrFail(),
        fn (string $searchValue): PaymentMethod => PaymentMethod::where('name', '!=', $searchValue)->firstOrFail(),
        fn (PaymentMethod $paymentMethod): string => $paymentMethod->name,
    ],
]);

dataset('payment_method_sortable_columns', [
    'by name' => 'name',
]);

dataset('payment_method_updated', [
    fn (PaymentMethod $paymentMethod): PaymentMethod => PaymentMethod::factory()->for($paymentMethod->distributor)->make([
        'name' => "{$paymentMethod->name} test",
    ]),
]);
