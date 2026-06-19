<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\Pages\CreateClient;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

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
                ->assertFormFieldExists('phone')
                ->assertFormFieldExists('carrier')
                ->assertFormFieldExists('buyer_name')
                ->assertFormFieldExists('buyer_contact');

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

                $clientOne = Client::factory()->for(Auth::user()->distributor)->create(['cpf_cnpj' => '12345678901']);
                $clientTwo = Client::factory()->make(['cpf_cnpj' => $clientOne->cpf_cnpj]);

                livewire(CreateClient::class)
                    ->fillForm($clientTwo->toArray())
                    ->call('create')
                    ->assertHasFormErrors(['cpf_cnpj'])
                    ->assertNotNotified()
                    ->assertNoRedirect();

            });

        });

    });

    describe('Actions', function (): void {

        it('can create a client', function (Client $client): void {
            $data = $client->withoutRelations()->toArray();

            livewire(CreateClient::class)
                ->fillForm($data)
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(Client::class, [
                ...$data,
                'distributor_id' => Auth::user()->distributor_id,
            ]);
        })->with('client_make_five_clients');

    });

});
