<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Support\Facades\Auth;

use function Pest\Livewire\livewire;

describe('Order View', function (): void {

    beforeEach(function (): void {
        $client = Client::factory()->for(Auth::user()->distributor)->create();

        Order::factory()->create([
            'client_id' => $client->id,
            'sales_representative_id' => SalesRepresentative::factory()->for(Auth::user()->distributor),
            'payment_method_id' => PaymentMethod::factory()->for(Auth::user()->distributor),
        ]);
    });

    it('can load the page', function (): void {

        $order = Order::firstOrFail();

        livewire(ViewOrder::class, ['record' => $order->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet([
                'status' => $order->status->label(), // obs: remover label e testar
                'notes' => $order->notes,
                'client_cpf_cnpj' => $order->client_cpf_cnpj,
                'client_corporate_name' => $order->client_corporate_name,
                'client_fantasy_name' => $order->client_fantasy_name,
                'salesRepresentative.name' => $order->salesRepresentative?->name,
                'payment_method_name' => $order->payment_method_name,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]);

    });

});
