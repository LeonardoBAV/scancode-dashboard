<?php

declare(strict_types=1);

use App\Models\Product;

dataset('product_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('product_make_five_products', [
    fn () => Product::factory()->make(),
    fn () => Product::factory()->make(),
    fn () => Product::factory()->make(),
    fn () => Product::factory()->make(),
    fn () => Product::factory()->make(),
]);

dataset('product_validations', [
    'sku required' => [
        fn () => Product::factory()->make(['sku' => null]),
        'errors' => ['sku' => 'required'],
    ],
    'barcode required' => [
        fn () => Product::factory()->make(['barcode' => null]),
        'errors' => ['barcode' => 'required'],
    ],
    'name required' => [
        fn () => Product::factory()->make(['name' => null]),
        'errors' => ['name' => 'required'],
    ],
    'price required' => [
        fn () => Product::factory()->make(['price' => null]),
        'errors' => ['price' => 'required'],
    ],
    'product_category_id required' => [
        fn () => Product::factory()->make(['product_category_id' => null]),
        'errors' => ['product_category_id' => 'required'],
    ],
]);

dataset('product_searchable_columns', [
    'by sku' => ['sku'],
    'by barcode' => ['barcode'],
    'by name' => ['name'],
]);
