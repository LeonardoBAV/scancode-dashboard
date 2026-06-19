<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

describe('Client model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protectedColumns): void {
        $tableColumns = Schema::getColumnListing((new Client)->getTable());
        $modelFillables = (new Client)->getFillable();

        $expectedInFillables = array_diff($tableColumns, $protectedColumns);

        $attributesMissing = array_diff($expectedInFillables, $modelFillables);
        $attributesOverfilled = array_intersect($modelFillables, $protectedColumns);

        expect($attributesMissing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributesMissing));
        expect($attributesOverfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributesOverfilled));

    })->with('client_protected_columns');

    describe('Relations', function (): void {

        beforeEach(function (): void {
            $client = Client::factory()->for($this->distributor)->create();

            Order::factory()->count(3)->create([
                'client_id' => $client->id,
                'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
                'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
            ]);
        });

        test('client has many orders', function (): void {
            $orders = Client::firstOrFail()->orders;

            expect($orders)->toBeInstanceOf(Collection::class);
            expect($orders)->toHaveCount(3);
            expect($orders)->each->toBeInstanceOf(Order::class);
        });

    });

});
