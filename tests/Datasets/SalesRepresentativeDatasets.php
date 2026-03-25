<?php

declare(strict_types=1);

use App\Models\SalesRepresentative;

dataset('sales_representative_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at', 'remember_token'],
    ],
]);

dataset('sales_representative_make_five_sales_representatives', [
    fn () => SalesRepresentative::factory()->make(['distributor_id' => null]),
    fn () => SalesRepresentative::factory()->make(['distributor_id' => null]),
    fn () => SalesRepresentative::factory()->make(['distributor_id' => null]),
    fn () => SalesRepresentative::factory()->make(['distributor_id' => null]),
    fn () => SalesRepresentative::factory()->make(['distributor_id' => null]),
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

dataset('sales_representative_searchable_columns', [// OBS: COLOCAR OS TIPOS DE RETORNOS
    'by cpf' => [
        fn (): SalesRepresentative => SalesRepresentative::whereNotNull('cpf')->firstOrFail(),
        fn (string $searchValue): SalesRepresentative => SalesRepresentative::where('cpf', '!=', $searchValue)->firstOrFail(),
        fn (SalesRepresentative $salesRepresentative): string => $salesRepresentative->cpf,
    ],
    'by name' => [
        fn (): SalesRepresentative => SalesRepresentative::whereNotNull('name')->firstOrFail(),
        fn (string $searchValue): SalesRepresentative => SalesRepresentative::where('name', '!=', $searchValue)->firstOrFail(),
        fn (SalesRepresentative $salesRepresentative): string => $salesRepresentative->name,
    ],
    'by email' => [
        fn (): SalesRepresentative => SalesRepresentative::whereNotNull('email')->firstOrFail(),
        fn (string $searchValue): SalesRepresentative => SalesRepresentative::where('email', '!=', $searchValue)->firstOrFail(),
        fn (SalesRepresentative $salesRepresentative): string => $salesRepresentative->email,
    ],
]);

dataset('sales_representative_updated', [
    fn (SalesRepresentative $salesRepresentative): SalesRepresentative => SalesRepresentative::factory()->for($salesRepresentative->distributor)->make([
        'cpf' => "{$salesRepresentative->cpf}1",
        'name' => "{$salesRepresentative->name}test",
        'email' => "{$salesRepresentative->email}test",
    ]),
]);
