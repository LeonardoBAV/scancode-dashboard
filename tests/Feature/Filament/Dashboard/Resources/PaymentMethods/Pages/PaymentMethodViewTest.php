<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\ViewPaymentMethod;
use App\Models\PaymentMethod;
use Illuminate\Support\Arr;

describe('PaymentMethod View', function (): void {

    beforeEach(function (): void {
        PaymentMethod::factory()->for($this->distributor)->create();
    });

    it('can load the page', function (): void {

        $paymentMethod = PaymentMethod::firstOrFail();
        $paymentMethodData = Arr::except($paymentMethod->toArray(), ['id', 'created_at', 'updated_at', 'distributor_id']);

        $this->livewireTenant(ViewPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet($paymentMethodData);

    });

});
