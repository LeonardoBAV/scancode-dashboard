<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\ViewProduct;
use App\Models\Product;

use function Pest\Livewire\livewire;

describe('Product View', function (): void {

    beforeEach(function (): void {
        Product::factory()->create();
    });

    it('can load the page', function (): void {

        $product = Product::firstOrFail();

        livewire(ViewProduct::class, ['record' => $product->getRouteKey()])
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
