<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\EditSalesRepresentative;
use App\Filament\Dashboard\Resources\SalesRepresentatives\SalesRepresentativeResource;
use App\Models\SalesRepresentative;
use Filament\Actions\DeleteAction;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('SalesRepresentative Edit', function (): void {

    beforeEach(function (): void {
        SalesRepresentative::factory()->create();
    });

    it('can load the page', function (): void {

        $salesRepresentative = SalesRepresentative::firstOrFail();

        livewire(EditSalesRepresentative::class, ['record' => $salesRepresentative->getRouteKey()])
            ->assertOk();

    });

    it('has a form', function (): void {

        $salesRepresentative = SalesRepresentative::firstOrFail();

        livewire(EditSalesRepresentative::class, ['record' => $salesRepresentative->getRouteKey()])
            ->assertSchemaExists('form')
            ->assertSchemaStateSet($salesRepresentative->toArray());

    });

    describe('Actions', function (): void {

        it('can update a sales representative', function (callable $fnSalesRepresentativeUpdated): void {

            $salesRepresentative = SalesRepresentative::firstOrFail();
            $salesRepresentativeUpdated = $fnSalesRepresentativeUpdated($salesRepresentative);

            livewire(EditSalesRepresentative::class, ['record' => $salesRepresentative->getRouteKey()])
                ->fillForm($salesRepresentativeUpdated->toArray())
                ->call('save')
                ->assertNotified()
                ->assertHasNoFormErrors();

            assertDatabaseHas(SalesRepresentative::class, $salesRepresentativeUpdated->toArray());

        })->with('sales_representative_updated');

        it('can delete a sales representative', function (): void {
            $salesRepresentative = SalesRepresentative::firstOrFail();

            livewire(EditSalesRepresentative::class, ['record' => $salesRepresentative->getRouteKey()])
                ->callAction(DeleteAction::class)
                ->assertNotified()
                ->assertRedirect(SalesRepresentativeResource::getUrl('index'));

            expect(SalesRepresentative::find($salesRepresentative->id))->toBeNull();
        });

    });

    describe('Validation', function (): void {

        it('cpf unique validation ignores the current sales representative', function (): void {

            $salesRepresentative = SalesRepresentative::factory()->create(['cpf' => '11111111111']);
            $salesRepresentativeUpdateData = SalesRepresentative::factory()->make(['cpf' => '11111111111']);

            livewire(EditSalesRepresentative::class, ['record' => $salesRepresentative->getRouteKey()])
                ->fillForm($salesRepresentativeUpdateData->toArray())
                ->call('save')
                ->assertHasNoFormErrors()
                ->assertNotified();

        });

    });

});
