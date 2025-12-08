<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Livewire\livewire;

describe('Order List', function (): void {

    beforeEach(function (): void {
        Order::factory()->count(10)->create();
    });

    it('can load the page', function (): void {

        livewire(ListOrders::class)
            ->assertSuccessful();
    });

    it('can list orders', function (): void {

        livewire(ListOrders::class)
            ->assertCanSeeTableRecords(Order::all())
            ->assertCountTableRecords(Order::count());
    });

    describe('Table:', function (): void {

        it('can render columns', function (): void {
            livewire(ListOrders::class)
                ->assertCanRenderTableColumn('status')
                ->assertCanRenderTableColumn('client.fantasy_name')
                ->assertCanRenderTableColumn('salesRepresentative.name')
                ->assertCanRenderTableColumn('paymentMethod.name')
                ->assertCanNotRenderTableColumn('created_at')
                ->assertCanNotRenderTableColumn('updated_at')

                ->toggleAllTableColumns()
                ->assertCanRenderTableColumn('created_at')
                ->assertCanRenderTableColumn('updated_at');
        });

        describe('Searchable:', function (): void {

            it('search is working', function (callable $fnOrder, callable $fnOrderNotFound, callable $fnValue): void {
                $order = $fnOrder();
                $searchValue = $fnValue($order);
                $orderNotFound = $fnOrderNotFound($searchValue);

                livewire(ListOrders::class)
                    ->assertCanSeeTableRecords(Order::all())
                    ->searchTable($searchValue)
                    ->assertCanSeeTableRecords([$order])
                    ->assertCanNotSeeTableRecords([$orderNotFound]);
            })->with('order_searchable_columns');
        });

        describe('Sortable:', function (): void {

            /*it('can sort orders by status', function (): void {//obs: verificar mais colunas para ser ordenado
                Order::query()->delete();
                Order::factory()->count(3)->create();

                $ordersAsc = Order::query()->orderBy('status', 'asc')->get();
                $ordersDesc = Order::query()->orderBy('status', 'desc')->get();

                livewire(ListOrders::class)
                    ->sortTable('status', 'asc')
                    ->assertCanSeeTableRecords($ordersAsc, inOrder: true)
                    ->sortTable('status', 'desc')
                    ->assertCanSeeTableRecords($ordersDesc, inOrder: true);
            });*/
        });

        describe('Bulk Actions:', function (): void { // obs: action passar para cancelar

            /*it('can bulk delete orders', function (): void {

                livewire(ListOrders::class)
                    ->assertCanSeeTableRecords(Order::all())
                    ->selectTableRecords(Order::all())
                    ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                    ->assertNotified()
                    ->assertCanNotSeeTableRecords(Order::all());
            });*/

        });

    });

});
