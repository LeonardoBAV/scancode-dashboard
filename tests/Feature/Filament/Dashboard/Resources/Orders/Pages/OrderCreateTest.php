<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Filament\Dashboard\Resources\Orders\Pages\CreateOrder;
use App\Models\Client;
use App\Models\Event;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('Order Create', function (): void {

    it('can load the page', function (): void {
        livewire(CreateOrder::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        livewire(CreateOrder::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            livewire(CreateOrder::class)
                ->assertFormFieldExists('event_id')
                ->assertFormFieldExists('client_id')
                ->assertFormFieldExists('sales_representative_id')
                ->assertFormFieldExists('payment_method_id')
                ->assertFormFieldExists('notes');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (Order $order, array $errors): void {

                livewire(CreateOrder::class)
                    ->fillForm(Arr::except($order->toArray(), ['buyer_name', 'buyer_phone']))
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('order_validations');

        });

    });

    describe('Actions', function (): void {

        it('can create an order', function (): void {
            $tenant = Auth::user()->distributor;

            $client = Client::factory()->for($tenant)->create([
                'buyer_name' => 'Comprador Teste',
                'buyer_contact' => '(11) 91234-5678',
            ]);

            $data = [
                'status' => OrderStatusEnum::PENDING,
                'event_id' => Event::factory()->for($tenant)->create()->id,
                'client_id' => $client->id,
                'sales_representative_id' => SalesRepresentative::factory()->for($tenant)->create()->id,
                'payment_method_id' => PaymentMethod::factory()->for($tenant)->create()->id,
                'notes' => fake()->sentence(),
            ];

            livewire(CreateOrder::class)
                ->fillForm($data)
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(Order::class, [...$data, 'distributor_id' => Auth::user()->distributor_id]);
            assertDatabaseHas(Order::class, [
                'client_id' => $client->id,
                'buyer_name' => $client->buyer_name,
                'buyer_phone' => $client->buyer_contact,
                'distributor_id' => Auth::user()->distributor_id,
            ]);

        });

    });
});
