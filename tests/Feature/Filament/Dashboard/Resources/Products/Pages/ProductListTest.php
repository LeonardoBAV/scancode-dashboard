<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\ListProducts;
use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Livewire\livewire;

describe('Product List', function (): void {

    beforeEach(function (): void {
        Product::factory()->count(10)->create([
            'distributor_id' => $this->distributor->id,
            'product_category_id' => ProductCategory::factory()->for($this->distributor),
        ]);
    });

    it('can load the page', function (): void {

        $this->livewireTenant(ListProducts::class)
            ->assertSuccessful();
    });

    it('can list products', function (): void {

        livewire(ListProducts::class)
            ->assertCanSeeTableRecords(Product::all())
            ->assertCountTableRecords(Product::count());
    });

    describe('Table:', function (): void {

        it('can render columns', function (): void {
            $this->livewireTenant(ListProducts::class)
                ->assertCanRenderTableColumn('name')
                ->assertCanRenderTableColumn('productCategory.name')
                ->assertCanRenderTableColumn('price')
                ->assertCanNotRenderTableColumn('created_at')
                ->assertCanNotRenderTableColumn('updated_at')

                ->toggleAllTableColumns()
                ->assertCanRenderTableColumn('created_at')
                ->assertCanRenderTableColumn('updated_at');
        });

        describe('Searchable:', function (): void {

            it('search is working', function (callable $fnProduct, callable $fnProductNotFound, callable $fnValue): void {
                /**
                 * @var callable():Product $fnProduct
                 * @var callable(string):Product $fnProductNotFound
                 * @var callable(Product):string $fnValue
                 */
                $product = $fnProduct();
                $searchValue = $fnValue($product);
                $productNotFound = $fnProductNotFound($searchValue);

                $this->livewireTenant(ListProducts::class)
                    ->assertCanSeeTableRecords(Product::all())
                    ->searchTable($searchValue)
                    ->assertCanSeeTableRecords([$product])
                    ->assertCanNotSeeTableRecords([$productNotFound]);
            })->with('product_searchable_columns');

        });

        describe('Sortable:', function (): void {

            it('can sort products', function (string $column): void {
                $productsAsc = Product::query()->orderBy($column, 'asc')->get();
                $productsDesc = Product::query()->orderBy($column, 'desc')->get();

                $this->livewireTenant(ListProducts::class)
                    ->sortTable($column, 'asc')
                    ->assertCanSeeTableRecords($productsAsc, inOrder: true)
                    ->sortTable($column, 'desc')
                    ->assertCanSeeTableRecords($productsDesc, inOrder: true);
            })->with('product_sortable_columns');
        });

        describe('Bulk Actions:', function (): void {

            it('can bulk delete products', function (): void {

                $this->livewireTenant(ListProducts::class)
                    ->assertCanSeeTableRecords(Product::all())
                    ->selectTableRecords(Product::all())
                    ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                    ->assertNotified()
                    ->assertCanNotSeeTableRecords(Product::all());
            });

        });

    });

});
