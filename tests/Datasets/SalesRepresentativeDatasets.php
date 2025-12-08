<?php

declare(strict_types=1);

use App\Models\SalesRepresentative;

dataset('sales_representative_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at', 'remember_token'],
    ],
]);

dataset('sales_representative_make_five_sales_representatives', [
    fn () => SalesRepresentative::factory()->make(),
    fn () => SalesRepresentative::factory()->make(),
    fn () => SalesRepresentative::factory()->make(),
    fn () => SalesRepresentative::factory()->make(),
    fn () => SalesRepresentative::factory()->make(),
]);

dataset('sales_representative_validations', [
    'cpf required' => [
        fn () => SalesRepresentative::factory()->make(['cpf' => null]),
        'errors' => ['cpf' => 'required'],
    ],
    'name required' => [
        fn () => SalesRepresentative::factory()->make(['name' => null]),
        'errors' => ['name' => 'required'],
    ],
    'email required' => [
        fn () => SalesRepresentative::factory()->make(['email' => null]),
        'errors' => ['email' => 'required'],
    ],
    'email invalid format' => [
        fn () => SalesRepresentative::factory()->make(['email' => 'invalid-email']),
        'errors' => ['email' => 'email'],
    ],
    'password required' => [
        fn () => SalesRepresentative::factory()->make(['password' => null]),
        'errors' => ['password' => 'required'],
    ],
]);

dataset('sales_representative_searchable_columns', [
    'by cpf' => [
        fn () => SalesRepresentative::whereNotNull('cpf')->first(),
        fn (string $searchValue) => SalesRepresentative::where('cpf', '!=', $searchValue)->first(),
        fn (SalesRepresentative $salesRepresentative) => $salesRepresentative->cpf,
    ],
    'by name' => [
        fn () => SalesRepresentative::whereNotNull('name')->first(),
        fn (string $searchValue) => SalesRepresentative::where('name', '!=', $searchValue)->first(),
        fn (SalesRepresentative $salesRepresentative) => $salesRepresentative->name,
    ],
    'by email' => [
        fn () => SalesRepresentative::whereNotNull('email')->first(),
        fn (string $searchValue) => SalesRepresentative::where('email', '!=', $searchValue)->first(),
        fn (SalesRepresentative $salesRepresentative) => $salesRepresentative->email,
    ],
]);

dataset('sales_representative_updated', [
    fn (SalesRepresentative $salesRepresentative) => SalesRepresentative::factory()->make([
        'cpf' => "{$salesRepresentative->cpf}1",
        'name' => "{$salesRepresentative->name}test",
        'email' => "{$salesRepresentative->email}test"
    ]),
]);
