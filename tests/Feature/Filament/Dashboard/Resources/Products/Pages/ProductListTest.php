<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Livewire\livewire;

describe('Product List', function (): void {

    beforeEach(function (): void {
        Product::factory()->count(10)->create();
    });

    it('can load the page', function (): void {

        livewire(ListProducts::class)
            ->assertSuccessful();
    });

    it('can list products', function (): void {

        livewire(ListProducts::class)
            ->assertCanSeeTableRecords(Product::all())
            ->assertCountTableRecords(Product::count());
    });

    describe('Table:', function (): void {

        it('can render columns', function (): void {
            livewire(ListProducts::class)
                ->assertCanRenderTableColumn('sku')
                ->assertCanRenderTableColumn('barcode')
                ->assertCanRenderTableColumn('name')
                ->assertCanRenderTableColumn('price')
                ->assertCanRenderTableColumn('productCategory.name')
                ->assertCanNotRenderTableColumn('created_at')
                ->assertCanNotRenderTableColumn('updated_at')

                ->toggleAllTableColumns()
                ->assertCanRenderTableColumn('created_at')
                ->assertCanRenderTableColumn('updated_at');
        });

        describe('Searchable:', function (): void {

            it('search is working', function (string $field): void {
                $product = Product::firstOrFail();
                $productNotFound = Product::where('id', '!=', $product->id)->first();

                $value = $product->getAttribute($field);
                $searchValue = is_string($value) ? $value : null;

                livewire(ListProducts::class)
                    ->searchTable($searchValue)
                    ->assertCanSeeTableRecords([$product])
                    ->assertCanNotSeeTableRecords([$productNotFound]);
            })->with('product_searchable_columns');

        });

        describe('Bulk Actions:', function (): void {

            it('can bulk delete products', function (): void {

                livewire(ListProducts::class)
                    ->assertCanSeeTableRecords(Product::all())
                    ->selectTableRecords(Product::all())
                    ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                    ->assertNotified()
                    ->assertCanNotSeeTableRecords(Product::all());
            });

        });

    });

});
