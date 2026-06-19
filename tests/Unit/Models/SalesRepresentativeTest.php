<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

describe('SalesRepresentative model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protectedColumns): void {
        $tableColumns = Schema::getColumnListing((new SalesRepresentative)->getTable());
        $modelFillables = (new SalesRepresentative)->getFillable();

        $expectedInFillables = array_diff($tableColumns, $protectedColumns);

        $attributesMissing = array_diff($expectedInFillables, $modelFillables);
        $attributesOverfilled = array_intersect($modelFillables, $protectedColumns);

        expect($attributesMissing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributesMissing));
        expect($attributesOverfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributesOverfilled));

    })->with('sales_representative_protected_columns');

    describe('Relations', function (): void {

        beforeEach(function (): void {
            $salesRepresentative = SalesRepresentative::factory()->for($this->distributor)->create();
            $client = Client::factory()->for($this->distributor)->create();

            Order::factory()->count(3)->create([
                'sales_representative_id' => $salesRepresentative->id,
                'client_id' => $client->id,
                'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
            ]);
        });

        test('sales representative has many orders', function (): void {
            $orders = SalesRepresentative::firstOrFail()->orders;

            expect($orders)->toBeInstanceOf(Collection::class);
            expect($orders)->toHaveCount(3);
            expect($orders)->each->toBeInstanceOf(Order::class);
        });

    });

});
