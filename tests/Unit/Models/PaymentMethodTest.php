<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

describe('PaymentMethod model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protectedColumns): void {
        $tableColumns = Schema::getColumnListing((new PaymentMethod)->getTable());
        $modelFillables = (new PaymentMethod)->getFillable();

        $expectedInFillables = array_diff($tableColumns, $protectedColumns);

        $attributesMissing = array_diff($expectedInFillables, $modelFillables);
        $attributesOverfilled = array_intersect($modelFillables, $protectedColumns);

        expect($attributesMissing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributesMissing));
        expect($attributesOverfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributesOverfilled));

    })->with('payment_method_protected_columns');

    describe('Relations', function (): void {

        beforeEach(function (): void {
            $paymentMethod = PaymentMethod::factory()->for($this->distributor)->create();
            $client = Client::factory()->for($this->distributor)->create();

            Order::factory()->count(3)->create([
                'payment_method_id' => $paymentMethod->id,
                'client_id' => $client->id,
                'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
            ]);
        });

        test('payment method has many orders', function (): void {
            $orders = PaymentMethod::firstOrFail()->orders;

            expect($orders)->toBeInstanceOf(Collection::class);
            expect($orders)->toHaveCount(3);
            expect($orders)->each->toBeInstanceOf(Order::class);
        });

    });

});
