<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\OrderResource;
use App\Filament\Dashboard\Resources\Orders\Pages\CreateOrder;
use App\Filament\Dashboard\Resources\Orders\Pages\EditOrder;
use App\Filament\Dashboard\Resources\Orders\Pages\ListOrders;
use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Resource - Order:', function (): void {

    beforeEach(function (): void {
        Order::factory()->create();
    });

    test('resource has correct model', function (): void {
        expect(OrderResource::getModel())->toBe(Order::class);
    });

    test('resource has record title attribute', function (): void {
        $titleAttribute = OrderResource::getRecordTitleAttribute();

        expect($titleAttribute)->toBe('id');
    });

    test('resource has correct pages configured', function (): void {
        $pages = OrderResource::getPages();

        expect($pages)->toHaveKey('index')
            ->and($pages)->toHaveKey('create')
            ->and($pages)->toHaveKey('view')
            ->and($pages)->toHaveKey('edit')
            ->and($pages['index']->getPage())->toBe(ListOrders::class)
            ->and($pages['create']->getPage())->toBe(CreateOrder::class)
            ->and($pages['view']->getPage())->toBe(ViewOrder::class)
            ->and($pages['edit']->getPage())->toBe(EditOrder::class);
    });

    test('index page loads correctly', function (): void {
        $url = OrderResource::getUrl('index');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ListOrders::class);
    });

    test('create page loads correctly', function (): void {
        $url = OrderResource::getUrl('create');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(CreateOrder::class);
    });

    test('view page loads correctly', function (): void {
        $order = Order::firstOrFail();

        $url = OrderResource::getUrl('view', ['record' => $order]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ViewOrder::class);
    });

    test('edit page loads correctly', function (): void {
        $order = Order::firstOrFail();

        $url = OrderResource::getUrl('edit', ['record' => $order]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(EditOrder::class);
    });

});
