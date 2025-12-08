<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\CreatePaymentMethod;
use App\Models\PaymentMethod;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('PaymentMethod Create', function (): void {

    it('can load the page', function (): void {
        livewire(CreatePaymentMethod::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        livewire(CreatePaymentMethod::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            livewire(CreatePaymentMethod::class)
                ->assertFormFieldExists('name');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (PaymentMethod $paymentMethod, array $errors): void {

                livewire(CreatePaymentMethod::class)
                    ->fillForm($paymentMethod->toArray())
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('payment_method_validations');

            it('name unique validation is working', function (): void {

                $paymentMethodOne = PaymentMethod::factory()->create(['name' => 'Cartão de Crédito']);
                $paymentMethodTwo = PaymentMethod::factory()->make(['name' => $paymentMethodOne->name]);

                livewire(CreatePaymentMethod::class)
                    ->fillForm($paymentMethodTwo->toArray())
                    ->call('create')
                    ->assertHasFormErrors(['name' => 'unique'])
                    ->assertNotNotified()
                    ->assertNoRedirect();

            });

        });

    });

    describe('Actions', function (): void {

        it('can create a payment method', function (PaymentMethod $paymentMethod): void {
            livewire(CreatePaymentMethod::class)
                ->fillForm($paymentMethod->toArray())
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(PaymentMethod::class, $paymentMethod->toArray());
        })->with('payment_method_make_five');

    });

});

