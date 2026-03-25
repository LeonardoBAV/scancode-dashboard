<?php

declare(strict_types=1);

use App\Models\Client;

dataset('client_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('client_make_five_clients', [
    fn () => Client::factory()->make(['distributor_id' => null]),
    fn () => Client::factory()->make(['distributor_id' => null]),
    fn () => Client::factory()->make(['distributor_id' => null]),
    fn () => Client::factory()->make(['distributor_id' => null]),
    fn () => Client::factory()->make(['distributor_id' => null]),
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
        fn (): Client => Client::whereNotNull('cpf_cnpj')->firstOrFail(),
        fn (string $searchValue): Client => Client::where('cpf_cnpj', '!=', $searchValue)->firstOrFail(),
        fn (Client $client): string => $client->cpf_cnpj ?? throw new UnexpectedValueException('CPF/CNPJ not found'),
    ],
    'by corporate_name' => [
        fn (): Client => Client::whereNotNull('corporate_name')->firstOrFail(),
        fn (string $searchValue): Client => Client::where('corporate_name', '!=', $searchValue)->firstOrFail(),
        fn (Client $client): string => $client->corporate_name ?? throw new UnexpectedValueException('Corporate name not found'),
    ],
    'by fantasy_name' => [
        fn (): Client => Client::whereNotNull('fantasy_name')->firstOrFail(),
        fn (string $searchValue): Client => Client::where('fantasy_name', '!=', $searchValue)->firstOrFail(),
        fn (Client $client): string => $client->fantasy_name ?? throw new UnexpectedValueException('Fantasy name not found'),
    ],
    'by email' => [
        fn (): Client => Client::whereNotNull('email')->firstOrFail(),
        fn (string $searchValue): Client => Client::where('email', '!=', $searchValue)->firstOrFail(),
        fn (Client $client): string => $client->email ?? throw new UnexpectedValueException('Email not found'),
    ],
    'by phone' => [
        fn (): Client => Client::whereNotNull('phone')->firstOrFail(),
        fn (string $searchValue): Client => Client::where('phone', '!=', $searchValue)->firstOrFail(),
        fn (Client $client): string => $client->phone ?? throw new UnexpectedValueException('Phone not found'),
    ],
    'by carrier' => [
        fn (): Client => Client::whereNotNull('carrier')->firstOrFail(),
        fn (string $searchValue): Client => Client::where('carrier', '!=', $searchValue)->firstOrFail(),
        fn (Client $client): string => $client->carrier ?? throw new UnexpectedValueException('Carrier not found'),
    ],
]);

dataset('client_updated', [
    fn (Client $client): Client => Client::factory()->for($client->distributor)->make([
        'cpf_cnpj' => "{$client->cpf_cnpj}test",
        'corporate_name' => "{$client->corporate_name} test",
        'fantasy_name' => "{$client->fantasy_name} test",
        'email' => "{$client->email}test",
        'phone' => "{$client->phone}1",
        'carrier' => "{$client->carrier} test",
    ]),
]);
