<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\Pages\CreateClient;
use App\Models\Client;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('Client Create', function (): void {

    it('can load the page', function (): void {
        livewire(CreateClient::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        livewire(CreateClient::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            livewire(CreateClient::class)
                ->assertFormFieldExists('cpf_cnpj')
                ->assertFormFieldExists('corporate_name')
                ->assertFormFieldExists('fantasy_name')
                ->assertFormFieldExists('email')
                ->assertFormFieldExists('phone');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (Client $client, array $errors): void {

                livewire(CreateClient::class)
                    ->fillForm($client->toArray())
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('client_validations');

            it('cpf cnpj unique validation is working', function (): void {

                $client_one = Client::factory()->make(['cpf_cnpj' => '12345678901']);
                $client_two = Client::factory()->make(['cpf_cnpj' => '12345678901']);

                livewire(CreateClient::class)
                    ->fillForm($client_one->toArray())
                    ->call('create');

                livewire(CreateClient::class)
                    ->fillForm($client_two->toArray())
                    ->call('create')
                    ->assertHasFormErrors(['cpf_cnpj' => 'unique'])
                    ->assertNotNotified()
                    ->assertNoRedirect();

            });

        });

    });

    describe('Actions', function (): void {

        it('can create a client', function (Client $client): void {
            $component = livewire(CreateClient::class)
                ->fillForm($client->toArray())
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(Client::class, $client->toArray());
        })->with('client_make_five_clients');

    });

});

// obs: melhorar datasets de validacoes apenas para required, para email para phone, lembrando que tem validacao de cpf cnpj unico tbm
