<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\EditOrder;
use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Support\Arr;

use function Pest\Laravel\assertDatabaseHas;

describe('Order Edit', function (): void {

    beforeEach(function (): void {
        $client = Client::factory()->for($this->distributor)->create();

        Order::factory()->create([
            'client_id' => $client->id,
            'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
            'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
        ]);
    });

    it('can load the page', function (): void {

        $order = Order::firstOrFail();

        $this->livewireTenant(EditOrder::class, ['record' => $order->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $order = Order::firstOrFail();

        $this->livewireTenant(EditOrder::class, ['record' => $order->getRouteKey()])
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

            $this->livewireTenant(EditOrder::class, ['record' => $order->getRouteKey()])
                ->fillForm($orderUpdated->toArray())
                ->call('save')
                ->assertNotified()
                ->assertHasNoFormErrors();

            assertDatabaseHas(Order::class, [
                ...Arr::except($orderUpdated->toArray(), ['distributor_id', 'id']),
                'distributor_id' => $this->distributor->id,
            ]);

        })->with('order_updated');

    });

});
