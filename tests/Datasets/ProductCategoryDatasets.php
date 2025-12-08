<?php

declare(strict_types=1);

use App\Models\ProductCategory;

dataset('product_category_protected_columns', [
    'protected_columns' => [
        ['id', 'created_at', 'updated_at'],
    ],
]);

dataset('product_category_updated', [
    fn (ProductCategory $productCategory) => ProductCategory::factory()->make([
        'name' => "{$productCategory->name} test",
    ]),
]);
