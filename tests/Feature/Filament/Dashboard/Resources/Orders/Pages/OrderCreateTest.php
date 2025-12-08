<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\CreateOrder;
use App\Models\Order;

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

        it('can create an order', function (): void {
            $order = Order::factory()->make();

            livewire(CreateOrder::class)
                ->fillForm($order->toArray())
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(Order::class, $order->toArray());
        });

    });
});
