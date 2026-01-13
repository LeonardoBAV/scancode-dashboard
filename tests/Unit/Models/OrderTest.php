<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

describe('Order model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protectedColumns): void {
        $tableColumns = Schema::getColumnListing((new Order)->getTable());
        $modelFillables = (new Order)->getFillable();

        $expectedInFillables = array_diff($tableColumns, $protectedColumns);

        $attributesMissing = array_diff($expectedInFillables, $modelFillables);
        $attributesOverfilled = array_intersect($modelFillables, $protectedColumns);

        expect($attributesMissing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributesMissing));
        expect($attributesOverfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributesOverfilled));

    })->with('order_protected_columns');

    describe('Relations', function (): void {

        beforeEach(function (): void {
            $order = Order::factory()->create(['status' => OrderStatusEnum::PENDING]);
            OrderItem::factory()->count(3)->create(['order_id' => $order->id]);
        });

        test('order belongs to a client', function (): void {
            expect(Order::firstOrFail()->client)->toBeInstanceOf(Client::class);
        });

        test('order belongs to a sales representative', function (): void {
            expect(Order::firstOrFail()->salesRepresentative)->toBeInstanceOf(SalesRepresentative::class);
        });

        test('order belongs to a payment method', function (): void {
            expect(Order::firstOrFail()->paymentMethod)->toBeInstanceOf(PaymentMethod::class);
        });

        test('order has many order items', function (): void {
            $orderItems = Order::firstOrFail()->orderItems;

            expect($orderItems)->toBeInstanceOf(Collection::class);
            expect($orderItems)->toHaveCount(3);
            expect($orderItems)->each->toBeInstanceOf(OrderItem::class);
        });
    });

});
