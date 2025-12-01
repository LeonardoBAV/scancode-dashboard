<?php

declare(strict_types=1);

use App\Models\SalesRepresentative;

/*
dataset('protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('make_five_clients', [
    fn () => SalesRepresentative::factory()->make(),
    fn () => SalesRepresentative::factory()->make(),
    fn () => SalesRepresentative::factory()->make(),
    fn () => SalesRepresentative::factory()->make(),
    fn () => SalesRepresentative::factory()->make(),
]);

dataset('validations', [
    'required' => [
        fn () => Client::factory()->make(['cpf_cnpj' => null]),
        'errors' => ['cpf_cnpj' => 'required'],
    ],
    'email' => [
        fn () => Client::factory()->make(['email' => 'invalid-email']),
        'errors' => ['email' => 'email'],
    ],
    'phone' => [
        fn () => Client::factory()->make(['phone' => 'invalid-phone']),
        'errors' => ['phone' => 'regex'],
    ],
]);

dataset('searchable_columns', [
    'by cpf_cnpj' => ['cpf_cnpj'],
    'by corporate_name' => ['corporate_name'],
    'by fantasy_name' => ['fantasy_name'],
    'by email' => ['email'],
    'by phone' => ['phone'],
]);*/
