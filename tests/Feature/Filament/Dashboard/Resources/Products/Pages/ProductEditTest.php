<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\EditProduct;
use App\Filament\Dashboard\Resources\Products\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Arr;

use function Pest\Laravel\assertDatabaseHas;

describe('Product Edit', function (): void {

    beforeEach(function (): void {
        Product::factory()->create([
            'distributor_id' => $this->distributor->id,
            'product_category_id' => ProductCategory::factory()->for($this->distributor),
        ]);
    });

    it('can load the page', function (): void {

        $product = Product::firstOrFail();

        $this->livewireTenant(EditProduct::class, ['record' => $product->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $product = Product::firstOrFail();

        $this->livewireTenant(EditProduct::class, ['record' => $product->getRouteKey()])
            ->assertSchemaExists('form')
            ->assertSchemaStateSet(Arr::except($product->toArray(), ['distributor_id']));

    });

    describe('Actions', function (): void {

        it('can update a product', function (callable $fnProductUpdated): void {
            /**
             * @var callable(Product):Product $fnProductUpdated
             */
            $product = Product::firstOrFail();
            $productUpdated = $fnProductUpdated($product);

            $this->livewireTenant(EditProduct::class, ['record' => $product->getRouteKey()])
                ->fillForm($productUpdated->toArray())
                ->call('save')
                ->assertNotified()
                ->assertHasNoFormErrors();

            assertDatabaseHas(Product::class, [
                ...$productUpdated->getAttributes(),
                'distributor_id' => $this->distributor->id,
            ]);

        })->with('product_updated');

        it('can delete a product', function (): void {
            $product = Product::firstOrFail();

            $this->livewireTenant(EditProduct::class, ['record' => $product->getRouteKey()])
                ->callAction(DeleteAction::class)
                ->assertNotified()
                ->assertRedirect(ProductResource::getUrl('index', tenant: $this->distributor));

            expect(Product::find($product->id))->toBeNull();
        });

    });

    describe('Validation', function (): void {

        it('barcode unique validation ignores the current product', function (): void {

            $product = Product::factory()->create([
                'barcode' => '1111111111111',
                'product_category_id' => ProductCategory::factory()->for($this->distributor),
            ]);
            $productUpdateData = Product::factory()->make(['barcode' => '1111111111111']);

            $this->livewireTenant(EditProduct::class, ['record' => $product->getRouteKey()])
                ->fillForm($productUpdateData->toArray())
                ->call('save')
                ->assertHasNoFormErrors()
                ->assertNotified();

        });

    });

    describe('Product Category', function (): void {

        it('can update a product category', function (callable $fnProductCategoryUpdated): void {
            /**
             * @var callable(ProductCategory):ProductCategory $fnProductCategoryUpdated
             */
            $product = Product::firstOrFail();
            $productCategory = $product->productCategory;
            $productCategoryUpdated = $fnProductCategoryUpdated($productCategory);

            $this->livewireTenant(EditProduct::class, ['record' => $product->getRouteKey()])
                ->callAction(
                    TestAction::make('editOption')
                        ->schemaComponent('product_category_id'),
                    data: $productCategoryUpdated->toArray())
                ->assertHasNoFormErrors()
                ->assertNotified();

            assertDatabaseHas(ProductCategory::class, [
                ...$productCategoryUpdated->getAttributes(),
                'distributor_id' => $this->distributor->id,
            ]);

        })->with('product_category_updated');

        it('validation is working', function (): void {

            $product = Product::firstOrFail();

            $this->livewireTenant(EditProduct::class, ['record' => $product->getRouteKey()])
                ->callAction(
                    TestAction::make('createOption')
                        ->schemaComponent('product_category_id'),
                    data: ['name' => null])
                ->assertHasFormErrors(['name' => 'required']);
        });
    });

});
