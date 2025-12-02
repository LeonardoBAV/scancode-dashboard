<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\ClientResource;
use App\Filament\Dashboard\Resources\Clients\Pages\CreateClient;
use App\Filament\Dashboard\Resources\Clients\Pages\EditClient;
use App\Filament\Dashboard\Resources\Clients\Pages\ListClients;
use App\Filament\Dashboard\Resources\Clients\Pages\ViewClient;
use App\Models\Client;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Resource - Client:', function (): void {

    beforeEach(function (): void {
        Client::factory()->create();
    });

    test('resource has correct model', function (): void {
        expect(ClientResource::getModel())->toBe(Client::class);
    });

    test('resource has record title attribute', function (): void {
        $titleAttribute = ClientResource::getRecordTitleAttribute();

        expect($titleAttribute)->toBe('corporate_name');
    });

    test('resource has correct pages configured', function (): void {
        $pages = ClientResource::getPages();

        expect($pages)->toHaveKey('index')
            ->and($pages)->toHaveKey('create')
            ->and($pages)->toHaveKey('view')
            ->and($pages)->toHaveKey('edit')
            ->and($pages['index']->getPage())->toBe(ListClients::class)
            ->and($pages['create']->getPage())->toBe(CreateClient::class)
            ->and($pages['view']->getPage())->toBe(ViewClient::class)
            ->and($pages['edit']->getPage())->toBe(EditClient::class);
    });

    test('index page loads correctly', function (): void {
        $url = ClientResource::getUrl('index');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ListClients::class);
    });

    test('create page loads correctly', function (): void {
        $url = ClientResource::getUrl('create');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(CreateClient::class);
    });

    test('view page loads correctly', function (): void {
        $client = Client::firstOrFail();

        $url = ClientResource::getUrl('view', ['record' => $client]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ViewClient::class);
    });

    test('edit page loads correctly', function (): void {
        $client = Client::firstOrFail();

        $url = ClientResource::getUrl('edit', ['record' => $client]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(EditClient::class);
    });

});
