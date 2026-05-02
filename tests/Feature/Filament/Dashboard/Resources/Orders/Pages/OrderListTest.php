<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Orders\Pages\ListOrders;
use App\Models\Client;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Auth;
use function Pest\Livewire\livewire;

describe('Order List', function (): void {

    beforeEach(function (): void {
        $tenantOrderAttributes = [
            'client_id' => Client::factory()->for(Auth::user()->distributor),
            'sales_representative_id' => SalesRepresentative::factory()->for(Auth::user()->distributor),
            'payment_method_id' => PaymentMethod::factory()->for(Auth::user()->distributor),
        ];

        Order::factory()->count(4)->create($tenantOrderAttributes);
        Order::factory()->count(3)->completed()->create($tenantOrderAttributes);
        Order::factory()->count(3)->cancelled()->create($tenantOrderAttributes);
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
                /**
                 * @var callable():Order $fnOrder
                 * @var callable(string):Order $fnOrderNotFound
                 * @var callable(Order):string $fnValue
                 */
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

            it('can sort orders by status', function (string $column): void {
                $ordersAsc = Order::query()->orderBy($column, 'asc')->orderBy('id', 'asc')->get();
                $ordersDesc = Order::query()->orderBy($column, 'desc')->orderBy('id', 'desc')->get();

                livewire(ListOrders::class)
                    ->sortTable($column, 'asc')
                    ->assertCanSeeTableRecords($ordersAsc, inOrder: true)
                    ->sortTable($column, 'desc')
                    ->assertCanSeeTableRecords($ordersDesc, inOrder: true);
            })->with('order_sortable_columns');

        });

        describe('Bulk Actions:', function (): void { // obs: action passar para cancelar

            /*it('can bulk delete orders', function (): void {

                $this->livewireTenant(ListOrders::class)
                    ->assertCanSeeTableRecords(Order::all())
                    ->selectTableRecords(Order::all())
                    ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                    ->assertNotified()
                    ->assertCanNotSeeTableRecords(Order::all());
            });*/

        });

    });

});
