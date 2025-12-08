<?php

declare(strict_types=1);

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

describe('ProductCategory model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protectedColumns): void {
        $tableColumns = Schema::getColumnListing((new ProductCategory)->getTable());
        $modelFillables = (new ProductCategory)->getFillable();

        $expectedInFillables = array_diff($tableColumns, $protectedColumns);

        $attributesMissing = array_diff($expectedInFillables, $modelFillables);
        $attributesOverfilled = array_intersect($modelFillables, $protectedColumns);

        expect($attributesMissing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributesMissing));
        expect($attributesOverfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributesOverfilled));

    })->with('product_category_protected_columns');

    describe('Relations', function (): void {

        beforeEach(function (): void {
            $productCategory = ProductCategory::factory()->create();
            Product::factory()->count(3)->create(['product_category_id' => $productCategory->id]);
        });

        test('product category has many products', function (): void {
            $products = ProductCategory::firstOrFail()->products;

            expect($products)->toBeInstanceOf(Collection::class);
            expect($products)->toHaveCount(3);
            expect($products)->each->toBeInstanceOf(Product::class);
        });

    });
});
