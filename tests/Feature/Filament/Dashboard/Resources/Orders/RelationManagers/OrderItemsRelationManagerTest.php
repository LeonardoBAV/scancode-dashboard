<?php

declare(strict_types=1);

use App\Enums\OrderStatusEnum;
use App\Filament\Dashboard\Resources\Orders\Pages\ViewOrder;
use App\Filament\Dashboard\Resources\Orders\RelationManagers\OrderItemsRelationManager;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\SalesRepresentative;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Testing\TestAction;
use Filament\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('OrderItem', function () {

    beforeEach(function () {
        $client = Client::factory()->for($this->distributor)->create();

        $order = Order::factory()->create([
            'status' => OrderStatusEnum::PENDING,
            'client_id' => $client->id,
            'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
            'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
        ]);

        OrderItem::factory()->count(10)->create([
            'order_id' => $order->id,
            'distributor_id' => $order->distributor_id,
            'product_id' => Product::factory()->state([
                'product_category_id' => ProductCategory::factory()->for($this->distributor),
            ]),
        ]);
    });

    it('can load the relation manager', function () {
        $order = Order::firstOrFail();

        $this->livewireTenant(ViewOrder::class, ['record' => $order->getRouteKey()])
            ->assertSeeLivewire(OrderItemsRelationManager::class);

    });

    it('can render the create action', function (): void { // obs ajustar para diferentes status
        $order = Order::query()->firstOrFail();
        // $order->toComplete();

        $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
            ->assertActionVisible(TestAction::make(CreateAction::class)->table());
    });

    describe('Table', function (): void {

        it('can load the list of order items', function () {
            $order = Order::query()->firstOrFail();

            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->assertOk()
                ->assertCanSeeTableRecords($order->orderItems)
                ->assertCountTableRecords($order->orderItems->count());
        });

        it('can render columns', function (): void {
            $order = Order::query()->firstOrFail();

            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->assertCanRenderTableColumn('product.name')
                ->assertCanRenderTableColumn('price')
                ->assertCanRenderTableColumn('qty')
                ->assertCanRenderTableColumn('total')
                ->assertCanNotRenderTableColumn('created_at')
                ->assertCanNotRenderTableColumn('updated_at')

                ->toggleAllTableColumns()
                ->assertCanRenderTableColumn('created_at')
                ->assertCanRenderTableColumn('updated_at');
        });

        it('can sort the list of order items', function (string $column): void {
            $order = Order::query()->firstOrFail();

            $orderItemsAsc = OrderItem::query()->orderBy($column, 'asc')->orderBy('id', 'asc')->get();
            $orderItemsDesc = OrderItem::query()->orderBy($column, 'desc')->orderBy('id', 'desc')->get();

            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->sortTable($column, 'asc')
                ->assertCanSeeTableRecords($orderItemsAsc, inOrder: true)
                ->sortTable($column, 'desc')
                ->assertCanSeeTableRecords($orderItemsDesc, inOrder: true);
        })->with('order_item_sortable_columns');

        it('can search the list of order items', function (callable $fnOrderItem, callable $fnOrderItemNotFound, callable $fnValue): void {
            /**
             * @var callable():OrderItem $fnOrderItem
             * @var callable(string):OrderItem $fnOrderItemNotFound
             * @var callable(OrderItem):string $fnValue
             */
            $order = Order::query()->firstOrFail();

            $orderItem = $fnOrderItem();
            $searchValue = $fnValue($orderItem);
            $orderItemNotFound = $fnOrderItemNotFound($searchValue);

            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->assertCanSeeTableRecords($order->orderItems)
                ->searchTable($searchValue)
                ->assertCanSeeTableRecords([$orderItem])
                ->assertCanNotSeeTableRecords([$orderItemNotFound]);
        })->with('order_item_searchable_columns');

        it('can bulk delete the list of order items', function (): void {
            $order = Order::query()->firstOrFail();

            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->assertCanSeeTableRecords($order->orderItems)
                ->selectTableRecords($order->orderItems)
                ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                ->assertNotified()
                ->assertCanNotSeeTableRecords($order->orderItems);
        });

        it('cannot bulk delete the list of order items if the order is not pending', function (): void {
            $order = Order::query()->latest('id')->firstOrFail();
            $order->toComplete();

            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->assertActionHidden(TestAction::make(DeleteBulkAction::class)->table()->bulk());

        });

        it('check the visibility of record actions when order is pending', function (): void {
            $client = Client::factory()->for($this->distributor)->create();
            $order = Order::factory()->create([
                'status' => OrderStatusEnum::PENDING,
                'client_id' => $client->id,
                'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
                'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
            ]);
            OrderItem::factory()->create([
                'order_id' => $order->id,
                'distributor_id' => $order->distributor_id,
                'product_id' => Product::factory()->state([
                    'product_category_id' => ProductCategory::factory()->for($this->distributor),
                ]),
            ]);
            $order->refresh();

            $livewire = $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->assertCanSeeTableRecords($order->orderItems);

            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), ViewAction::class, true);
            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), EditAction::class, true);
            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), DeleteAction::class, true);
        });

        it('check the visibility of record actions when order is completed', function (): void {
            $client = Client::factory()->for($this->distributor)->create();
            $order = Order::factory()->create([
                'status' => OrderStatusEnum::PENDING,
                'client_id' => $client->id,
                'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
                'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
            ]);
            OrderItem::factory()->create([
                'order_id' => $order->id,
                'distributor_id' => $order->distributor_id,
                'product_id' => Product::factory()->state([
                    'product_category_id' => ProductCategory::factory()->for($this->distributor),
                ]),
            ]);
            $order->refresh();
            $order->toComplete();

            $livewire = $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->assertCanSeeTableRecords($order->orderItems);

            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), ViewAction::class, true);
            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), EditAction::class, false);
            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), DeleteAction::class, false);
        });

        it('check the visibility of record actions when order is cancelled', function (): void {
            $client = Client::factory()->for($this->distributor)->create();
            $order = Order::factory()->create([
                'status' => OrderStatusEnum::PENDING,
                'client_id' => $client->id,
                'sales_representative_id' => SalesRepresentative::factory()->for($this->distributor),
                'payment_method_id' => PaymentMethod::factory()->for($this->distributor),
            ]);
            OrderItem::factory()->create([
                'order_id' => $order->id,
                'distributor_id' => $order->distributor_id,
                'product_id' => Product::factory()->state([
                    'product_category_id' => ProductCategory::factory()->for($this->distributor),
                ]),
            ]);
            $order->refresh();
            $order->toCancel();

            $livewire = $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->assertCanSeeTableRecords($order->orderItems);

            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), ViewAction::class, true);
            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), EditAction::class, false);
            assertTableRecordActionVisibility($livewire, $order->orderItems->first(), DeleteAction::class, false);
        });

    });

    describe('form', function (): void {

        describe('Create', function (): void {

            it('has all fields', function (): void {
                $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => Order::query()->firstOrFail(), 'pageClass' => ViewOrder::class])
                    ->mountAction(TestAction::make(CreateAction::class)->table())
                    ->assertFormFieldExists('product_id')
                    ->assertFormFieldExists('price')
                    ->assertFormFieldExists('qty')
                    ->assertFormFieldExists('notes');
            });

            it('can create an order item', function (OrderItem $orderItem): void {
                $data = $orderItem->withoutRelations()->toArray();

                livewire(OrderItemsRelationManager::class, ['ownerRecord' => $orderItem->order, 'pageClass' => ViewOrder::class])
                    ->callAction(
                        TestAction::make(CreateAction::class)->table(),
                        data: $data
                    )
                    ->assertHasNoFormErrors()
                    ->assertNotified();

                assertDatabaseHas(OrderItem::class, [...$data, 'distributor_id' => Auth::user()->distributor_id]);

            })->with('order_item_make_five_order_items');

        });

    });

    describe('Edit', function (): void {

        it('has all fields', function (): void {
            $order = Order::query()->firstOrFail();

            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
                ->mountAction(TestAction::make(EditAction::class)->table($order->orderItems->first()))
                ->assertFormFieldExists('product_id')
                ->assertFormFieldExists('price')
                ->assertFormFieldExists('qty')
                ->assertFormFieldExists('notes');
        });

        it('can update an order item', function (callable $fnOrderItemUpdated): void {

            /**
             * @var callable(OrderItem):OrderItem $fnOrderItemUpdated
             */
            $orderItem = OrderItem::firstOrFail();
            $orderItemUpdated = $fnOrderItemUpdated($orderItem);

            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => $orderItem->order, 'pageClass' => ViewOrder::class])
                ->callAction(
                    TestAction::make(EditAction::class)->table($orderItem),
                    data: $orderItemUpdated->toArray()
                )
                ->assertHasNoFormErrors()
                ->assertNotified();

            assertDatabaseHas(OrderItem::class, $orderItemUpdated->toArray());

        })->with('order_item_updated');

        it('basic validations are working', function (array $data, array $errors): void {
            $this->livewireTenant(OrderItemsRelationManager::class, ['ownerRecord' => Order::query()->firstOrFail(), 'pageClass' => ViewOrder::class])
                ->callAction(
                    TestAction::make(CreateAction::class)->table(),
                    data: $data
                )
                ->assertHasFormErrors($errors)
                ->assertNotNotified();
        })->with('order_item_validations'); // obs: validation de acao de edit deveria ser separado de create podem ter regras diferentes pensar nisto apesar de ser mesmo for ou fazer um dataset
    });

});
