<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

describe('Product model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protectedColumns): void {
        $tableColumns = Schema::getColumnListing((new Product)->getTable());
        $modelFillables = (new Product)->getFillable();

        $expectedInFillables = array_diff($tableColumns, $protectedColumns);

        $attributesMissing = array_diff($expectedInFillables, $modelFillables);
        $attributesOverfilled = array_intersect($modelFillables, $protectedColumns);

        expect($attributesMissing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributesMissing));
        expect($attributesOverfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributesOverfilled));

    })->with('product_protected_columns');

    describe('Relations', function (): void {

        beforeEach(function (): void {
            $product = Product::factory()->create([
                'product_category_id' => ProductCategory::factory()->for($this->distributor),
            ]);

            $client = Client::factory()->for($this->distributor)->create();

            $order = Order::factory()->create([
                'client_id' => $client->id,
                'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
                'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
            ]);

            OrderItem::factory()->count(3)->createQuietly([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'distributor_id' => $this->distributor->id,
            ]);
        });

        test('product belongs to a product category', function (): void {
            expect(Product::firstOrFail()->productCategory)->toBeInstanceOf(ProductCategory::class);
        });

        test('product has many order items', function (): void {
            $orderItems = Product::firstOrFail()->orderItems;

            expect($orderItems)->toBeInstanceOf(Collection::class);
            expect($orderItems)->toHaveCount(3);
            expect($orderItems)->each->toBeInstanceOf(OrderItem::class);
        });

    });

});
