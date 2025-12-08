<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\ListSalesRepresentatives;
use App\Models\SalesRepresentative;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Livewire\livewire;

describe('SalesRepresentative List', function (): void {

    beforeEach(function (): void {
        SalesRepresentative::factory()->count(10)->create();
    });

    it('can load the page', function (): void {

        livewire(ListSalesRepresentatives::class)
            ->assertSuccessful();
    });

    it('can list sales representatives', function (): void {

        livewire(ListSalesRepresentatives::class)
            ->assertCanSeeTableRecords(SalesRepresentative::all())
            ->assertCountTableRecords(SalesRepresentative::count());
    });

    describe('Table:', function (): void {

        it('can render columns', function (): void {
            livewire(ListSalesRepresentatives::class)
                ->assertCanRenderTableColumn('cpf')
                ->assertCanRenderTableColumn('name')
                ->assertCanRenderTableColumn('email')
                ->assertCanNotRenderTableColumn('created_at')
                ->assertCanNotRenderTableColumn('updated_at')

                ->toggleAllTableColumns()
                ->assertCanRenderTableColumn('created_at')
                ->assertCanRenderTableColumn('updated_at');
        });

        describe('Searchable:', function (): void {

            it('search is working', function (callable $getValue, callable $loadNotFound): void {
                $salesRepresentative = SalesRepresentative::firstOrFail();
                $searchValue = $getValue($salesRepresentative);
                $salesRepresentativeNotFound = $loadNotFound($searchValue);

                livewire(ListSalesRepresentatives::class)
                    ->assertCanSeeTableRecords(SalesRepresentative::all())
                    ->searchTable($searchValue)
                    ->assertCanSeeTableRecords([$salesRepresentative])
                    ->assertCanNotSeeTableRecords([$salesRepresentativeNotFound]);
            })->with('sales_representative_searchable_columns');

        });

        describe('Bulk Actions:', function (): void {

            it('can bulk delete sales representatives', function (): void {

                livewire(ListSalesRepresentatives::class)
                    ->assertCanSeeTableRecords(SalesRepresentative::all())
                    ->selectTableRecords(SalesRepresentative::all())
                    ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                    ->assertNotified()
                    ->assertCanNotSeeTableRecords(SalesRepresentative::all());
            });

        });

    });

});
