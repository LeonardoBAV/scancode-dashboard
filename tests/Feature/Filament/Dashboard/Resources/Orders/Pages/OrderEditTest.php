<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\EditOrder;
use App\Models\Order;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('Order Edit', function (): void {

    beforeEach(function (): void {
        Order::factory()->create();
    });

    it('can load the page', function (): void {

        $order = Order::firstOrFail();

        livewire(EditOrder::class, ['record' => $order->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $order = Order::firstOrFail();

        livewire(EditOrder::class, ['record' => $order->getRouteKey()])
            ->assertSchemaExists('form')
            ->assertSchemaStateSet([
                'client_id' => $order->client_id,
                'sales_representative_id' => $order->sales_representative_id,
                'payment_method_id' => $order->payment_method_id,
                'notes' => $order->notes,
            ]);

    });

    describe('Actions', function (): void {

        it('can update an order', function (callable $fnOrderUpdated): void {
            /**
             * @var callable(Order):Order $fnOrderUpdated
             */
            $order = Order::firstOrFail();
            $orderUpdated = $fnOrderUpdated($order);

            livewire(EditOrder::class, ['record' => $order->getRouteKey()])
                ->fillForm($orderUpdated->toArray())
                ->call('save')
                ->assertNotified()
                ->assertHasNoFormErrors();

            assertDatabaseHas(Order::class, $orderUpdated->toArray());

        })->with('order_updated');

    });

});
