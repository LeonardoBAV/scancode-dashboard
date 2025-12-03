<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\EditProduct;
use App\Filament\Dashboard\Resources\Products\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Livewire\livewire;

describe('Product Edit', function (): void {

    beforeEach(function (): void {
        Product::factory()->create();
    });

    it('can load the page', function (): void {

        $product = Product::firstOrFail();

        livewire(EditProduct::class, ['record' => $product->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $product = Product::firstOrFail();

        livewire(EditProduct::class, ['record' => $product->getRouteKey()])
            ->assertSchemaExists('form')
            ->assertSchemaStateSet($product->toArray());

    });

    describe('Actions', function (): void {

        it('can update a product', function (): void {

            $product = Product::factory()->create([
                'sku' => 'SKU-111111',
                'barcode' => '1111111111111',
                'name' => 'Original Product 1',
                'price' => 100.00,
            ]);

            $productUpdateData = Product::factory()->make([
                'sku' => 'SKU-222222',
                'barcode' => '2222222222222',
                'name' => 'Updated Product 2',
                'price' => 200.00,
            ]);

            livewire(EditProduct::class, ['record' => $product->getRouteKey()])
                ->fillForm($productUpdateData->toArray())
                ->call('save')
                ->assertNotified();

            $product = $product->refresh();
            expect($product->sku)->toBe($productUpdateData->sku)
                ->and($product->barcode)->toBe($productUpdateData->barcode)
                ->and($product->name)->toBe($productUpdateData->name)
                ->and($product->price)->toBe($productUpdateData->price);

        });

        it('can delete a product', function (): void {
            $product = Product::firstOrFail();

            livewire(EditProduct::class, ['record' => $product->getRouteKey()])
                ->callAction(DeleteAction::class)
                ->assertNotified()
                ->assertRedirect(ProductResource::getUrl('index'));

            expect(Product::find($product->id))->toBeNull();
        });

    });

    describe('Validation', function (): void {

        it('barcode unique validation ignores the current product', function (): void {

            $product = Product::factory()->create(['barcode' => '1111111111111']);
            $productUpdateData = Product::factory()->make(['barcode' => '1111111111111']);

            livewire(EditProduct::class, ['record' => $product->getRouteKey()])
                ->fillForm($productUpdateData->toArray())
                ->call('save')
                ->assertHasNoFormErrors()
                ->assertNotified();

        });

    });

    describe('Product Category', function (): void {

        beforeEach(function (): void {
            ProductCategory::factory()->create();
        });

        it('can update a product category', function (): void {

            $productCategory = ProductCategory::firstOrFail();
            $name = "{$productCategory->name} Updated";

            livewire(EditProduct::class, ['record' => $productCategory->getRouteKey()])
                ->callAction(
                    TestAction::make('editOption')
                        ->schemaComponent('product_category_id'),
                    data: ['name' => $name])
                ->assertHasNoFormErrors()
                ->assertNotified();

            $productCategory = $productCategory->refresh();
            expect($productCategory->name)->toBe($name);

        });

        it('validation is working', function (): void {

            $productCategory = ProductCategory::firstOrFail();

            livewire(EditProduct::class, ['record' => $productCategory->getRouteKey()])
                ->callAction(
                    TestAction::make('createOption')
                        ->schemaComponent('product_category_id'),
                    data: ['name' => null])
                ->assertHasFormErrors(['name' => 'required']);
        });
    });

});
