<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\ViewProduct;
use App\Models\Product;
use App\Models\ProductCategory;

describe('Product View', function (): void {

    beforeEach(function (): void {
        Product::factory()->create([
            'distributor_id' => $this->distributor->id,
            'product_category_id' => ProductCategory::factory()->for($this->distributor),
        ]);
    });

    it('can load the page', function (): void {

        $product = Product::firstOrFail();

        $this->livewireTenant(ViewProduct::class, ['record' => $product->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet([
                'sku' => $product->sku,
                'barcode' => $product->barcode,
                'name' => $product->name,
                'price' => $product->price,
                'productCategory.name' => $product->productCategory?->name,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ]);

    });

});
