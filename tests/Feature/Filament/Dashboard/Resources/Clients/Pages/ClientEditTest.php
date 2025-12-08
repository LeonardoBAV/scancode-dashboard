<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\ClientResource;
use App\Filament\Dashboard\Resources\Clients\Pages\EditClient;
use App\Models\Client;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;

use function Pest\Livewire\livewire;

describe('Client Edit', function (): void {

    beforeEach(function (): void {
        Client::factory()->create();
    });

    it('can load the page', function (): void {

        $client = Client::firstOrFail();

        livewire(EditClient::class, ['record' => $client->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $client = Client::firstOrFail();

        livewire(EditClient::class, ['record' => $client->getRouteKey()])
            ->assertSchemaExists('form')
            ->assertSchemaStateSet($client->toArray());

    });

    describe('Actions', function (): void {

        it('can update a client', function (callable $fnClientUpdated): void { // obs: can be improved get registter already exists and update it

            $client = Client::firstOrFail();
            $clientUpdated = $fnClientUpdated($client);

            livewire(EditClient::class, ['record' => $client->getRouteKey()])
                ->fillForm($clientUpdated->toArray())
                ->call('save')
                ->assertNotified()
                ->assertHasNoFormErrors();

            $client = $client->refresh();
            expect($client->cpf_cnpj)->toBe($clientUpdated->cpf_cnpj)
                ->and($client->corporate_name)->toBe($clientUpdated->corporate_name)
                ->and($client->fantasy_name)->toBe($clientUpdated->fantasy_name)
                ->and($client->email)->toBe($clientUpdated->email)
                ->and($client->phone)->toBe($clientUpdated->phone)
                ->and($client->carrier)->toBe($clientUpdated->carrier);

        })->with('client_updated');

        it('can delete a client', function (): void {
            $client = Client::firstOrFail();

            livewire(EditClient::class, ['record' => $client->getRouteKey()])
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

            livewire(EditClient::class, ['record' => $client->getRouteKey()])
                ->fillForm($clientUpdateData->toArray())
                ->call('save')
                ->assertHasNoFormErrors()
                ->assertNotified();

        });

    });

});

/*


test('has view action in header', function () {
    livewire(EditClient::class, [
        'record' => $this->client->getRouteKey(),
    ])
        ->assertActionExists(ViewAction::class);
});

test('has delete action in header', function () {
    livewire(EditClient::class, [
        'record' => $this->client->getRouteKey(),
    ])
        ->assertActionExists(DeleteAction::class);
});

test('can delete client from edit page', function () {
    livewire(EditClient::class, [
        'record' => $this->client->getRouteKey(),
    ])
        ->callAction(DeleteAction::class)
        ->assertSuccessful();

    expect(Client::find($this->client->id))->toBeNull();
});
*/
