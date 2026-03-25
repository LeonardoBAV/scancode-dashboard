<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\CreatePaymentMethod;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('PaymentMethod Create', function (): void {

    it('can load the page', function (): void {
        $this->livewireTenant(CreatePaymentMethod::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        $this->livewireTenant(CreatePaymentMethod::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            $this->livewireTenant(CreatePaymentMethod::class)
                ->assertFormFieldExists('name');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (PaymentMethod $paymentMethod, array $errors): void {

                $this->livewireTenant(CreatePaymentMethod::class)
                    ->fillForm($paymentMethod->toArray())
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('payment_method_validations');

            it('name unique validation is working', function (): void {

                $paymentMethodOne = PaymentMethod::factory()->for($this->distributor)->create(['name' => 'Cartão de Crédito']);
                $paymentMethodTwo = PaymentMethod::factory()->for($this->distributor)->make(['name' => $paymentMethodOne->name]);

                $this->livewireTenant(CreatePaymentMethod::class)
                    ->fillForm($paymentMethodTwo->toArray())
                    ->call('create')
                    ->assertHasFormErrors(['name'])
                    ->assertNotNotified()
                    ->assertNoRedirect();

            });

        });

    });

    describe('Actions', function (): void {

        it('can create a payment method', function (PaymentMethod $paymentMethod): void {
            $data = $paymentMethod->withoutRelations()->toArray();

            livewire(CreatePaymentMethod::class)
                ->fillForm($data)
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(PaymentMethod::class, [...$data, 'distributor_id' => Auth::user()->distributor_id]);

        })->with('payment_method_make_five');

    });

});
