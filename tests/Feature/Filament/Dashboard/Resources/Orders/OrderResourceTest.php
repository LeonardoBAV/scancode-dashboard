<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\OrderResource;
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
            ->and($pages)->toHaveKey('view')
            ->and($pages['index']->getPage())->toBe(ListOrders::class)
            ->and($pages['view']->getPage())->toBe(ViewOrder::class);
    });

    test('index page loads correctly', function (): void {
        $url = OrderResource::getUrl('index');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ListOrders::class);
    });

    test('view page loads correctly', function (): void {
        $order = Order::firstOrFail();

        $url = OrderResource::getUrl('view', ['record' => $order]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ViewOrder::class);
    });

});
