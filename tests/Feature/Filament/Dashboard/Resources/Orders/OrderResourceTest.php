<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\OrderResource;
use App\Filament\Dashboard\Resources\Orders\Pages\ListOrders;
use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;

describe('Resource - Order:', function (): void {

    beforeEach(function (): void {
        $client = Client::factory()->for($this->distributor)->create();

        Order::factory()->create([
            'client_id' => $client->id,
            'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
            'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
        ]);
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
        $url = OrderResource::getUrl('index', tenant: $this->distributor);

        $this->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ListOrders::class);
    });

    test('view page loads correctly', function (): void {
        $order = Order::firstOrFail();

        $url = OrderResource::getUrl('view', ['record' => $order], tenant: $this->distributor);

        $this->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ViewOrder::class);
    });

});
