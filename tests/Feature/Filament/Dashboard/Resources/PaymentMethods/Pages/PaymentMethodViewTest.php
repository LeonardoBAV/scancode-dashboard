<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\ViewPaymentMethod;
use App\Models\PaymentMethod;
use Illuminate\Support\Arr;

use function Pest\Livewire\livewire;

describe('PaymentMethod View', function (): void {

    beforeEach(function (): void {
        PaymentMethod::factory()->create();
    });

    it('can load the page', function (): void {

        $paymentMethod = PaymentMethod::firstOrFail();
        $paymentMethodData = Arr::except($paymentMethod->toArray(), ['id', 'created_at', 'updated_at']);

        livewire(ViewPaymentMethod::class, ['record' => $paymentMethod->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet($paymentMethodData);

    });

});

