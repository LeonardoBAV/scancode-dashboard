<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\CreatePaymentMethod;
use App\Filament\Dashboard\Resources\PaymentMethods\Pages\EditPaymentMethod;
use App\Filament\Dashboard\Resources\PaymentMethods\Pages\ListPaymentMethods;
use App\Filament\Dashboard\Resources\PaymentMethods\Pages\ViewPaymentMethod;
use App\Filament\Dashboard\Resources\PaymentMethods\PaymentMethodResource;
use App\Models\PaymentMethod;

describe('Resource - PaymentMethod:', function (): void {

    beforeEach(function (): void {
        PaymentMethod::factory()->for($this->distributor)->create();
    });

    test('resource has correct model', function (): void {
        expect(PaymentMethodResource::getModel())->toBe(PaymentMethod::class);
    });

    test('resource has record title attribute', function (): void {
        $titleAttribute = PaymentMethodResource::getRecordTitleAttribute();

        expect($titleAttribute)->toBe('name');
    });

    test('resource has correct pages configured', function (): void {
        $pages = PaymentMethodResource::getPages();

        expect($pages)->toHaveKey('index')
            ->and($pages)->toHaveKey('create')
            ->and($pages)->toHaveKey('view')
            ->and($pages)->toHaveKey('edit')
            ->and($pages['index']->getPage())->toBe(ListPaymentMethods::class)
            ->and($pages['create']->getPage())->toBe(CreatePaymentMethod::class)
            ->and($pages['view']->getPage())->toBe(ViewPaymentMethod::class)
            ->and($pages['edit']->getPage())->toBe(EditPaymentMethod::class);
    });

    test('index page loads correctly', function (): void {
        $url = PaymentMethodResource::getUrl('index', tenant: $this->distributor);

        $this->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ListPaymentMethods::class);
    });

    test('create page loads correctly', function (): void {
        $url = PaymentMethodResource::getUrl('create', tenant: $this->distributor);

        $this->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(CreatePaymentMethod::class);
    });

    test('view page loads correctly', function (): void {
        $paymentMethod = PaymentMethod::firstOrFail();

        $url = PaymentMethodResource::getUrl('view', ['record' => $paymentMethod], tenant: $this->distributor);

        $this->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ViewPaymentMethod::class);
    });

    test('edit page loads correctly', function (): void {
        $paymentMethod = PaymentMethod::firstOrFail();

        $url = PaymentMethodResource::getUrl('edit', ['record' => $paymentMethod], tenant: $this->distributor);

        $this->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(EditPaymentMethod::class);
    });

});
