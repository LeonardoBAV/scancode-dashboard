<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\Pages\ViewClient;
use App\Models\Client;
use Filament\Actions\EditAction;
use Illuminate\Support\Arr;

use function Pest\Livewire\livewire;

describe('Client View', function (): void {

    beforeEach(function (): void {
        Client::factory()->create();
    });

    it('can load the page', function (): void {

        $client = Client::firstOrFail();
        $clientData = Arr::except($client->toArray(), ['id', 'created_at', 'updated_at']);

        livewire(ViewClient::class, ['record' => $client->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet($clientData);

    });

});

/*

test('has edit action in header', function () {
    livewire(ViewClient::class, [
        'record' => $this->client->getRouteKey(),
    ])
        ->assertActionExists(EditAction::class);
});

test('can navigate to edit page', function () {
    livewire(ViewClient::class, [
        'record' => $this->client->getRouteKey(),
    ])
        ->callAction(EditAction::class)
        ->assertSuccessful();
});
*/
