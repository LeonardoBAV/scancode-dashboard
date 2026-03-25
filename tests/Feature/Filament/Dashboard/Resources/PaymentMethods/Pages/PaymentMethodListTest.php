<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\ListPaymentMethods;
use App\Models\PaymentMethod;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

describe('PaymentMethod List', function (): void {

    beforeEach(function (): void {
        PaymentMethod::factory()->count(10)->create();
    });

    it('can load the page', function (): void {

        $this->livewireTenant(ListPaymentMethods::class)
            ->assertSuccessful();
    });

    it('can list payment methods', function (): void {

        $this->livewireTenant(ListPaymentMethods::class)
            ->assertCanSeeTableRecords(PaymentMethod::all())
            ->assertCountTableRecords(PaymentMethod::count());
    });

    describe('Table:', function (): void {

        it('can render columns', function (): void {
            $this->livewireTenant(ListPaymentMethods::class)
                ->assertCanRenderTableColumn('name')
                ->assertCanNotRenderTableColumn('created_at')
                ->assertCanNotRenderTableColumn('updated_at')

                ->toggleAllTableColumns()
                ->assertCanRenderTableColumn('created_at')
                ->assertCanRenderTableColumn('updated_at');
        });

        describe('Searchable:', function (): void {

            it('search is working', function (callable $fnPaymentMethod, callable $fnPaymentMethodNotFound, callable $fnValue): void {
                /**
                 * @var callable():PaymentMethod $fnPaymentMethod
                 * @var callable(string):PaymentMethod $fnPaymentMethodNotFound
                 * @var callable(PaymentMethod):string $fnValue
                 */
                $paymentMethod = $fnPaymentMethod();
                $searchValue = $fnValue($paymentMethod);
                $paymentMethodNotFound = $fnPaymentMethodNotFound($searchValue);

                $this->livewireTenant(ListPaymentMethods::class)
                    ->assertCanSeeTableRecords(PaymentMethod::all())
                    ->searchTable($searchValue)
                    ->assertCanSeeTableRecords([$paymentMethod])
                    ->assertCanNotSeeTableRecords([$paymentMethodNotFound]);
            })->with('payment_method_searchable_columns');

        });

        describe('Sortable:', function (): void {

            it('can sort payment methods', function (string $column): void {
                $paymentMethodsAsc = PaymentMethod::query()->orderBy($column, 'asc')->get();
                $paymentMethodsDesc = PaymentMethod::query()->orderBy($column, 'desc')->get();

                $this->livewireTenant(ListPaymentMethods::class)
                    ->sortTable($column, 'asc')
                    ->assertCanSeeTableRecords($paymentMethodsAsc, inOrder: true)
                    ->sortTable($column, 'desc')
                    ->assertCanSeeTableRecords($paymentMethodsDesc, inOrder: true);
            })->with('payment_method_sortable_columns');
        });

        describe('Bulk Actions:', function (): void {

            it('can bulk delete payment methods', function (): void {

                $this->livewireTenant(ListPaymentMethods::class)
                    ->assertCanSeeTableRecords(PaymentMethod::all())
                    ->selectTableRecords(PaymentMethod::all())
                    ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                    ->assertNotified()
                    ->assertCanNotSeeTableRecords(PaymentMethod::all());
            });

        });

    });

});
