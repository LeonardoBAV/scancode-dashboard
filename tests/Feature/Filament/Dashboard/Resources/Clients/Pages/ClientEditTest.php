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

        it('can update a client', function (): void { // obs: can be improved get registter already exists and update it

            $client = Client::factory()->create([
                'cpf_cnpj' => '11111111111',
                'corporate_name' => 'Original Company 1',
                'fantasy_name' => 'Original Fantasy 1',
                'email' => 'original1@example.com',
                'phone' => '11111111111',
            ]);

            $client_update_data = Client::factory()->make([
                'cpf_cnpj' => '22222222222',
                'corporate_name' => 'Original Company 2',
                'fantasy_name' => 'Updated Fantasy 2',
                'email' => 'updated2@example.com',
                'phone' => '22222222222',
            ]);

            livewire(EditClient::class, ['record' => $client->getRouteKey()])
                ->fillForm($client_update_data->toArray())
                ->call('save')
                ->assertNotified();

            $client = $client->refresh();
            expect($client->cpf_cnpj)->toBe($client_update_data->cpf_cnpj)
                ->and($client->corporate_name)->toBe($client_update_data->corporate_name)
                ->and($client->fantasy_name)->toBe($client_update_data->fantasy_name)
                ->and($client->email)->toBe($client_update_data->email)
                ->and($client->phone)->toBe($client_update_data->phone);

        });

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
            $client_update_data = Client::factory()->make(['cpf_cnpj' => '11111111111']);

            livewire(EditClient::class, ['record' => $client->getRouteKey()])
                ->fillForm($client_update_data->toArray())
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
