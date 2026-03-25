<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Filament\Dashboard\Resources\Orders\Pages\CreateOrder;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('Order Create', function (): void {

    it('can load the page', function (): void {
        $this->livewireTenant(CreateOrder::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        $this->livewireTenant(CreateOrder::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            $this->livewireTenant(CreateOrder::class)
                ->assertFormFieldExists('client_id')
                ->assertFormFieldExists('sales_representative_id')
                ->assertFormFieldExists('payment_method_id')
                ->assertFormFieldExists('notes');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (Order $order, array $errors): void {

                $this->livewireTenant(CreateOrder::class)
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
            $data = Order::factory()->make(['distributor_id' => null, 'status' => OrderStatusEnum::PENDING])->withoutRelations()->toArray();

            livewire(CreateOrder::class)
                ->fillForm($data)
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(Order::class, [...$data, 'distributor_id' => Auth::user()->distributor_id]);

        });

    });
});
