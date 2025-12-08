<?php

declare(strict_types=1);

use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
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

    test('order belongs to a client', function (): void {
        $order = Order::factory()->create();

        expect($order->client)->toBeInstanceOf(Client::class);
    });

    test('order belongs to a sales representative', function (): void {
        $order = Order::factory()->create();

        expect($order->salesRepresentative)->toBeInstanceOf(SalesRepresentative::class);
    });

    test('order belongs to a payment method', function (): void {
        $order = Order::factory()->create();

        expect($order->paymentMethod)->toBeInstanceOf(PaymentMethod::class);
    });

    test('order can have many order items', function (): void {//obs: este nao esta muito correto e aproveitar verificar testes de outra relacoes de outra models
        $order = Order::factory()->create();

        expect($order->orderItems)->toBeEmpty();
    });

});
