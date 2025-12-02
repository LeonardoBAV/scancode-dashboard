<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\CreateSalesRepresentative;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\EditSalesRepresentative;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\ListSalesRepresentatives;
use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\ViewSalesRepresentative;
use App\Filament\Dashboard\Resources\SalesRepresentatives\SalesRepresentativeResource;
use App\Models\SalesRepresentative;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Resource - SalesRepresentative:', function (): void {

    test('resource has correct model', function (): void {
        expect(SalesRepresentativeResource::getModel())->toBe(SalesRepresentative::class);
    });

    test('resource has record title attribute', function (): void {
        $titleAttribute = SalesRepresentativeResource::getRecordTitleAttribute();

        expect($titleAttribute)->toBe('name');
    });

    test('resource has correct pages configured', function (): void {
        $pages = SalesRepresentativeResource::getPages();

        expect($pages)->toHaveKey('index')
            ->and($pages)->toHaveKey('create')
            ->and($pages)->toHaveKey('view')
            ->and($pages)->toHaveKey('edit')
            ->and($pages['index']->getPage())->toBe(ListSalesRepresentatives::class)
            ->and($pages['create']->getPage())->toBe(CreateSalesRepresentative::class)
            ->and($pages['view']->getPage())->toBe(ViewSalesRepresentative::class)
            ->and($pages['edit']->getPage())->toBe(EditSalesRepresentative::class);
    });

    test('index page loads correctly', function (): void {
        $url = SalesRepresentativeResource::getUrl('index');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ListSalesRepresentatives::class);
    });

    test('create page loads correctly', function (): void {
        $url = SalesRepresentativeResource::getUrl('create');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(CreateSalesRepresentative::class);
    });

    test('view page loads correctly', function (): void {
        $salesRepresentative = SalesRepresentative::factory()->create();

        $url = SalesRepresentativeResource::getUrl('view', ['record' => $salesRepresentative]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ViewSalesRepresentative::class);
    });

    test('edit page loads correctly', function (): void {
        $salesRepresentative = SalesRepresentative::factory()->create();

        $url = SalesRepresentativeResource::getUrl('edit', ['record' => $salesRepresentative]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(EditSalesRepresentative::class);
    });

});
