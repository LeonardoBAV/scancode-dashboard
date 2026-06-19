<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\EditPaymentMethod;
use App\Filament\Dashboard\Resources\PaymentMethods\PaymentMethodResource;
use App\Models\PaymentMethod;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Arr;

use function Pest\Laravel\assertDatabaseHas;

describe('PaymentMethod Edit', function (): void {

    beforeEach(function (): void {
        PaymentMethod::factory()->for($this->distributor)->create();
    });

    it('can load the page', function (): void {

        $paymentMethod = PaymentMethod::firstOrFail();

        $this->livewireTenant(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $paymentMethod = PaymentMethod::firstOrFail();

        $this->livewireTenant(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
            ->assertSchemaExists('form')
            ->assertSchemaStateSet(Arr::except($paymentMethod->toArray(), ['distributor_id']));

    });

    describe('Actions', function (): void {

        it('can update a payment method', function (callable $fnPaymentMethodUpdated): void {
            /**
             * @var callable(PaymentMethod):PaymentMethod $fnPaymentMethodUpdated
             */
            $paymentMethod = PaymentMethod::firstOrFail();
            $paymentMethodUpdated = $fnPaymentMethodUpdated($paymentMethod);

            $this->livewireTenant(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
                ->fillForm($paymentMethodUpdated->toArray())
                ->call('save')
                ->assertNotified()
                ->assertHasNoFormErrors();

            assertDatabaseHas(PaymentMethod::class, [
                ...Arr::except($paymentMethodUpdated->toArray(), ['distributor_id', 'id']),
                'distributor_id' => $this->distributor->id,
            ]);
        })->with('payment_method_updated');

        it('can delete a payment method', function (): void {
            $paymentMethod = PaymentMethod::firstOrFail();

            $this->livewireTenant(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
                ->callAction(DeleteAction::class)
                ->assertNotified()
                ->assertRedirect(PaymentMethodResource::getUrl('index'));

            expect(PaymentMethod::find($paymentMethod->id))->toBeNull();
        });

    });

    describe('Validation', function (): void {

        it('name unique validation ignores the current payment method', function (): void {

            $paymentMethod = PaymentMethod::firstOrFail();
            $paymentMethodUpdateData = PaymentMethod::factory()->make(['name' => $paymentMethod->name]);

            $this->livewireTenant(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
                ->fillForm($paymentMethodUpdateData->toArray())
                ->call('save')
                ->assertHasNoFormErrors()
                ->assertNotified();

        });

    });

});
