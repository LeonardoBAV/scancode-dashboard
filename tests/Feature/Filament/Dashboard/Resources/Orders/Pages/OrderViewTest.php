<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;

use function Pest\Livewire\livewire;

describe('Order View', function (): void {

    beforeEach(function (): void {
        Order::factory()->create();
    });

    it('can load the page', function (): void {//obs: verificar mais campos

        $order = Order::firstOrFail();

        livewire(ViewOrder::class, ['record' => $order->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet([
                'status' => $order->status,
                'notes' => $order->notes,
            ]);

    });

});
