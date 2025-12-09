<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;

use function Pest\Livewire\livewire;

describe('Order View', function (): void {

    beforeEach(function (): void {
        Order::factory()->create();
    });

    it('can load the page', function (): void {

        $order = Order::firstOrFail();

        livewire(ViewOrder::class, ['record' => $order->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet([
                'status' => $order->status,
                'notes' => $order->notes,
                'client.fantasy_name' => $order->client?->fantasy_name,
                'salesRepresentative.name' => $order->salesRepresentative?->name,
                'paymentMethod.name' => $order->paymentMethod?->name,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]);

    });

});
