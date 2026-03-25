<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;

describe('Order View', function (): void {

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

        $this->livewireTenant(ViewOrder::class, ['record' => $order->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet([
                'status' => $order->status->label(), // obs: remover label e testar
                'notes' => $order->notes,
                'client.fantasy_name' => $order->client?->fantasy_name,
                'salesRepresentative.name' => $order->salesRepresentative?->name,
                'paymentMethod.name' => $order->paymentMethod?->name,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]);

    });

});
