<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\ClientResource;
use App\Filament\Dashboard\Resources\Clients\Pages\EditClient;
use App\Models\Client;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Arr;

use function Pest\Laravel\assertDatabaseHas;

describe('Client Edit', function (): void {

    beforeEach(function (): void {
        Client::factory()->for($this->distributor)->create();
    });

    it('can load the page', function (): void {

        $client = Client::firstOrFail();

        $this->livewireTenant(EditClient::class, ['record' => $client->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $client = Client::firstOrFail();

        $this->livewireTenant(EditClient::class, ['record' => $client->getRouteKey()])
            ->assertSchemaExists('form')
            ->assertSchemaStateSet(Arr::except($client->toArray(), ['distributor_id']));

    });

    describe('Actions', function (): void {

        it('can update a client', function (callable $fnClientUpdated): void {
            /**
             * @var callable(Client):Client $fnClientUpdated
             */
            $client = Client::firstOrFail();
            $clientUpdated = $fnClientUpdated($client);

            $this->livewireTenant(EditClient::class, ['record' => $client->getRouteKey()])
                ->fillForm($clientUpdated->toArray())
                ->call('save')
                ->assertNotified()
                ->assertHasNoFormErrors();

            assertDatabaseHas(Client::class, [
                ...Arr::except($clientUpdated->toArray(), ['distributor_id', 'id']),
                'distributor_id' => $this->distributor->id,
            ]);

        })->with('client_updated');

        it('can delete a client', function (): void {
            $client = Client::firstOrFail();

            $this->livewireTenant(EditClient::class, ['record' => $client->getRouteKey()])
                ->callAction(DeleteAction::class)
                ->assertNotified()
                ->assertRedirect(ClientResource::getUrl('index'));

            expect(Client::find($client->id))->toBeNull();
        });

    });

    describe('Validation', function (): void {

        it('cpf cnpj unique validation ignores the current client', function (): void {

            $client = Client::factory()->create(['cpf_cnpj' => '11111111111']);
            $clientUpdateData = Client::factory()->make(['cpf_cnpj' => '11111111111']);

            $this->livewireTenant(EditClient::class, ['record' => $client->getRouteKey()])
                ->fillForm($clientUpdateData->toArray())
                ->call('save')
                ->assertHasNoFormErrors()
                ->assertNotified();

        });

    });

});

/*


test('has view action in header', function () {
    $this->livewireTenant(EditClient::class, [
        'record' => $this->client->getRouteKey(),
    ])
        ->assertActionExists(ViewAction::class);
});

test('has delete action in header', function () {
    $this->livewireTenant(EditClient::class, [
        'record' => $this->client->getRouteKey(),
    ])
        ->assertActionExists(DeleteAction::class);
});

test('can delete client from edit page', function () {
    $this->livewireTenant(EditClient::class, [
        'record' => $this->client->getRouteKey(),
    ])
        ->callAction(DeleteAction::class)
        ->assertSuccessful();

    expect(Client::find($this->client->id))->toBeNull();
});
*/
