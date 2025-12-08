<?php

declare(strict_types=1);

use App\Models\PaymentMethod;

dataset('payment_method_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('payment_method_make_five', [
    fn () => PaymentMethod::factory()->make(),
    fn () => PaymentMethod::factory()->make(),
    fn () => PaymentMethod::factory()->make(),
    fn () => PaymentMethod::factory()->make(),
    fn () => PaymentMethod::factory()->make(),
]);

dataset('payment_method_validations', [
    'required' => [
        fn () => PaymentMethod::factory()->make(['name' => null]),
        'errors' => ['name' => 'required'],
    ],
]);

dataset('payment_method_searchable_columns', [
    'by name' => [
        fn () => PaymentMethod::whereNotNull('name')->first(),
        fn (string $searchValue) => PaymentMethod::where('name', '!=', $searchValue)->first(),
        fn (PaymentMethod $paymentMethod) => $paymentMethod->name,
    ],
]);

dataset('payment_method_sortable_columns', [
    'by name' => 'name',
]);
