<?php

declare(strict_types=1);

use App\Models\Client;

dataset('client_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('client_make_five_clients', [
    fn () => Client::factory()->make(),
    fn () => Client::factory()->make(),
    fn () => Client::factory()->make(),
    fn () => Client::factory()->make(),
    fn () => Client::factory()->make(),
]);

dataset('client_validations', [
    'required' => [
        fn () => Client::factory()->make(['cpf_cnpj' => null, 'corporate_name' => null]),
        'errors' => ['cpf_cnpj' => 'required', 'corporate_name' => 'required'],
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

dataset('client_searchable_columns', [
    'by cpf_cnpj' => ['cpf_cnpj'],
    'by corporate_name' => ['corporate_name'],
    'by fantasy_name' => ['fantasy_name'],
    'by email' => ['email'],
    'by phone' => ['phone'],
    'by carrier' => ['carrier'],
]);
