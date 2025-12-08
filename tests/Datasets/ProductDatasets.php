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
        fn () => Product::factory()->make(['sku' => null, 'name' => null, 'price' => null]),
        'errors' => ['sku' => 'required', 'name' => 'required', 'price' => 'required'],
    ],
]);

dataset('product_searchable_columns', [
    'by name' => [
        fn (Product $product) => $product->name,
        fn (string $searchValue) => Product::where('name', '!=', $searchValue)->first(),
    ],
]);

dataset('product_sortable_columns', [
    'by price' => 'price',
    'name' => 'name',
]);
