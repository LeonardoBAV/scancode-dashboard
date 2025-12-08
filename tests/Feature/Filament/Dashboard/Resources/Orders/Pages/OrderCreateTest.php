<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\CreateOrder;
use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;

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
                ->assertFormFieldExists('status')
                ->assertFormFieldExists('client_id')
                ->assertFormFieldExists('sales_representative_id')
                ->assertFormFieldExists('payment_method_id')
                ->assertFormFieldExists('notes');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (Order $order, array $errors): void {

                livewire(CreateOrder::class)
                    ->fillForm($order->toArray())
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('order_validations');

        });

    });

    describe('Actions', function (): void {

        it('can create an order', function (): void {// obs: colcoar no dataset

            $client = Client::factory()->create();
            $salesRepresentative = SalesRepresentative::factory()->create();
            $paymentMethod = PaymentMethod::factory()->create();

            $orderData = [
                'status' => 'pending',
                'client_id' => $client->id,
                'sales_representative_id' => $salesRepresentative->id,
                'payment_method_id' => $paymentMethod->id,
                'notes' => 'Test order notes',
            ];

            livewire(CreateOrder::class)
                ->fillForm($orderData)
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(Order::class, [
                'status' => 'pending',
                'client_id' => $client->id,
                'sales_representative_id' => $salesRepresentative->id,
                'payment_method_id' => $paymentMethod->id,
                'notes' => 'Test order notes',
            ]);
        });

    });
});
