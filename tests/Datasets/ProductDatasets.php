<?php

declare(strict_types=1);

use App\Models\Product;

dataset('product_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('product_make_five_products', [
    fn () => Product::factory()->make(['distributor_id' => null]),
    fn () => Product::factory()->make(['distributor_id' => null]),
    fn () => Product::factory()->make(['distributor_id' => null]),
    fn () => Product::factory()->make(['distributor_id' => null]),
    fn () => Product::factory()->make(['distributor_id' => null]),
]);

dataset('product_validations', [
    'sku required' => [
        fn () => Product::factory()->make(['sku' => null, 'name' => null, 'price' => null]),
        'errors' => ['sku' => 'required', 'name' => 'required', 'price' => 'required'],
    ],
]);

dataset('product_searchable_columns', [
    'by name' => [
        fn (): Product => Product::whereNotNull('name')->firstOrFail(),
        fn (string $searchValue): Product => Product::where('name', '!=', $searchValue)->firstOrFail(),
        fn (Product $product): string => $product->name,
    ],
]);

dataset('product_sortable_columns', [
    'by price' => 'price',
    'by name' => 'name',
]);

dataset('product_updated', [
    fn (Product $product): Product => Product::factory()->make([
        'sku' => "{$product->sku}test",
        'barcode' => "{$product->barcode}1",
        'name' => "{$product->name} test",
        'price' => $product->price + 100,
    ]),
]);
