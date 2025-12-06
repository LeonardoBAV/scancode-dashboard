<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\EditPaymentMethod;
use App\Filament\Dashboard\Resources\PaymentMethods\PaymentMethodResource;
use App\Models\PaymentMethod;
use Filament\Actions\DeleteAction;

use function Pest\Livewire\livewire;

describe('PaymentMethod Edit', function (): void {

    beforeEach(function (): void {
        PaymentMethod::factory()->create();
    });

    it('can load the page', function (): void {

        $paymentMethod = PaymentMethod::firstOrFail();

        livewire(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $paymentMethod = PaymentMethod::firstOrFail();

        livewire(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
            ->assertSchemaExists('form')
            ->assertSchemaStateSet($paymentMethod->toArray());

    });

    describe('Actions', function (): void {

        it('can update a payment method', function (): void {

            $paymentMethod = PaymentMethod::factory()->create();

            $name = "{$paymentMethod->name} updated";

            livewire(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
                ->fillForm(['name' => $name])
                ->call('save')
                ->assertNotified();

            $paymentMethod = $paymentMethod->refresh();
            expect($paymentMethod->name)->toBe($name);

        });

        it('can delete a payment method', function (): void {
            $paymentMethod = PaymentMethod::firstOrFail();

            livewire(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
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

            livewire(EditPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
                ->fillForm($paymentMethodUpdateData->toArray())
                ->call('save')
                ->assertHasNoFormErrors()
                ->assertNotified();

        });

    });

});
