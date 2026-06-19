<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\Pages\ViewClient;
use App\Models\Client;
use Illuminate\Support\Arr;

describe('Client View', function (): void {

    beforeEach(function (): void {
        Client::factory()->for($this->distributor)->create();
    });

    it('can load the page', function (): void {

        $client = Client::firstOrFail();
        $clientData = Arr::except($client->toArray(), ['id', 'created_at', 'updated_at', 'distributor_id']);

        $this->livewireTenant(ViewClient::class, ['record' => $client->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet($clientData);

    });

});
