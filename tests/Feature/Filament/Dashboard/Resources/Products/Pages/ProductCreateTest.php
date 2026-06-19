<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\CreateProduct;
use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('Product Create', function (): void {

    it('can load the page', function (): void {
        $this->livewireTenant(CreateProduct::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        $this->livewireTenant(CreateProduct::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            $this->livewireTenant(CreateProduct::class)
                ->assertFormFieldExists('sku')
                ->assertFormFieldExists('barcode')
                ->assertFormFieldExists('name')
                ->assertFormFieldExists('price')
                ->assertFormFieldExists('product_category_id');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (Product $product, array $errors): void {

                $this->livewireTenant(CreateProduct::class)
                    ->fillForm($product->toArray())
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('product_validations');

            it('barcode unique validation is working', function (): void {

                $productOne = Product::factory()->create([
                    'barcode' => '1234567890123',
                    'product_category_id' => ProductCategory::factory()->for($this->distributor),
                ]);
                $productTwo = Product::factory()->make([
                    'barcode' => $productOne->barcode,
                    'product_category_id' => $productOne->product_category_id,
                ]);

                $this->livewireTenant(CreateProduct::class)
                    ->fillForm($productTwo->toArray())
                    ->call('create')
                    ->assertHasFormErrors(['barcode'])
                    ->assertNotNotified()
                    ->assertNoRedirect();

            });

        });

    });

    describe('Actions', function (): void {

        it('can create a product', function (Product $product): void {
            $data = $product->withoutRelations()->toArray();

            livewire(CreateProduct::class)
                ->fillForm($data)
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(Product::class, [...$data, 'distributor_id' => Auth::user()->distributor_id]);

        })->with('product_make_five_products');

    });

    describe('Product Category', function (): void {

        it('can create a new product category', function (): void {

            $data = ProductCategory::factory()->make(['distributor_id' => Auth::user()->distributor_id])->withoutRelations()->toArray();

            livewire(CreateProduct::class)
                ->callAction(
                    TestAction::make('createOption')
                        ->schemaComponent('product_category_id'),
                    data: $data)
                ->assertHasNoFormErrors()
                ->assertNotified();

            assertDatabaseHas(ProductCategory::class, $data);
        });

        it('validation is working', function (): void {

            $this->livewireTenant(CreateProduct::class)
                ->callAction(
                    TestAction::make('createOption')
                        ->schemaComponent('product_category_id'),
                    data: ['name' => null])
                ->assertHasFormErrors(['name' => 'required']);
        });
    });
});
