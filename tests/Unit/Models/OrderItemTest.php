<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SalesRepresentative;
use Illuminate\Support\Facades\Schema;

describe('OrderItem model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protectedColumns): void {
        $tableColumns = Schema::getColumnListing((new OrderItem)->getTable());
        $modelFillables = (new OrderItem)->getFillable();

        $expectedInFillables = array_diff($tableColumns, $protectedColumns);

        $attributesMissing = array_diff($expectedInFillables, $modelFillables);
        $attributesOverfilled = array_intersect($modelFillables, $protectedColumns);

        expect($attributesMissing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributesMissing));
        expect($attributesOverfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributesOverfilled));

    })->with('order_item_protected_columns');

    describe('Relations', function (): void {

        beforeEach(function (): void {
            $client = Client::factory()->for($this->distributor)->create();

            $order = Order::factory()->create([
                'status' => OrderStatusEnum::PENDING,
                'client_id' => $client->id,
                'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
                'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
            ]);

            OrderItem::factory()->create([
                'order_id' => $order->id,
                'distributor_id' => $order->distributor_id,
                'product_id' => Product::factory()->state([
                    'product_category_id' => ProductCategory::factory()->for($this->distributor),
                ]),
            ]);
        });

        test('order item belongs to an order', function (): void {
            expect(OrderItem::firstOrFail()->order)->toBeInstanceOf(Order::class);
        });

        test('order item belongs to a product', function (): void {
            expect(OrderItem::firstOrFail()->product)->toBeInstanceOf(Product::class);
        });

    });

});
