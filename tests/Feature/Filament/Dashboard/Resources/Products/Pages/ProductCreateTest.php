<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\CreateProduct;
use App\Models\Product;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('Product Create', function (): void {

    it('can load the page', function (): void {
        livewire(CreateProduct::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        livewire(CreateProduct::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            livewire(CreateProduct::class)
                ->assertFormFieldExists('sku')
                ->assertFormFieldExists('barcode')
                ->assertFormFieldExists('name')
                ->assertFormFieldExists('price')
                ->assertFormFieldExists('product_category_id');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (Product $product, array $errors): void {

                livewire(CreateProduct::class)
                    ->fillForm($product->toArray())
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('product_validations');

            it('barcode unique validation is working', function (): void {

                $productOne = Product::factory()->create(['barcode' => '1234567890123']);
                $productTwo = Product::factory()->make(['barcode' => $productOne->barcode]);

                livewire(CreateProduct::class)
                    ->fillForm($productTwo->toArray())
                    ->call('create')
                    ->assertHasFormErrors(['barcode' => 'unique'])
                    ->assertNotNotified()
                    ->assertNoRedirect();

            });

        });

    });

    describe('Actions', function (): void {

        it('can create a product', function (Product $product): void {
            livewire(CreateProduct::class)
                ->fillForm($product->toArray())
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(Product::class, $product->toArray());
        })->with('product_make_five_products');

    });

});
