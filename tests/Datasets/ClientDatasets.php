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
    'by cpf_cnpj' => [
        fn () => Client::whereNotNull('cpf_cnpj')->first(),
        fn (string $searchValue) => Client::where('cpf_cnpj', '!=', $searchValue)->first(),
        fn (Client $client) => $client->cpf_cnpj,
    ],
    'by corporate_name' => [
        fn () => Client::whereNotNull('corporate_name')->first(),
        fn (string $searchValue) => Client::where('corporate_name', '!=', $searchValue)->first(),
        fn (Client $client) => $client->corporate_name,
    ],
    'by fantasy_name' => [
        fn () => Client::whereNotNull('fantasy_name')->first(),
        fn (string $searchValue) => Client::where('fantasy_name', '!=', $searchValue)->first(),
        fn (Client $client) => $client->fantasy_name,
    ],
    'by email' => [
        fn () => Client::whereNotNull('email')->first(),
        fn (string $searchValue) => Client::where('email', '!=', $searchValue)->first(),
        fn (Client $client) => $client->email,
    ],
    'by phone' => [
        fn () => Client::whereNotNull('phone')->first(),
        fn (string $searchValue) => Client::where('phone', '!=', $searchValue)->first(),
        fn (Client $client) => $client->phone,
    ],
    'by carrier' => [
        fn () => Client::whereNotNull('carrier')->first(),
        fn (string $searchValue) => Client::where('carrier', '!=', $searchValue)->first(),
        fn (Client $client) => $client->carrier,
    ],
]);

dataset('client_updated', [
    fn (Client $client) => Client::factory()->make([
        'cpf_cnpj' => "{$client->cpf_cnpj}test",
        'corporate_name' => "{$client->corporate_name} test",
        'fantasy_name' => "{$client->fantasy_name} test",
        'email' => "{$client->email}test",
        'phone' => "{$client->phone}1",
        'carrier' => "{$client->carrier} test",
    ]),
]);
