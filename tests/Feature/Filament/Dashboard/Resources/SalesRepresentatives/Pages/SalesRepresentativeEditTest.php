<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\EditSalesRepresentative;
use App\Filament\Dashboard\Resources\SalesRepresentatives\SalesRepresentativeResource;
use App\Models\SalesRepresentative;
use Filament\Actions\DeleteAction;

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

        it('can update a sales representative', function (): void {

            $salesRepresentative = SalesRepresentative::factory()->create([
                'cpf' => '11111111111',
                'name' => 'Original Name 1',
                'email' => 'original1@example.com',
                'password' => bcrypt('password123'),
            ]);

            $salesRepresentativeUpdateData = SalesRepresentative::factory()->make([
                'cpf' => '22222222222',
                'name' => 'Updated Name 2',
                'email' => 'updated2@example.com',
                'password' => bcrypt('newpassword456'),
            ]);

            livewire(EditSalesRepresentative::class, ['record' => $salesRepresentative->getRouteKey()])
                ->fillForm($salesRepresentativeUpdateData->toArray())
                ->call('save')
                ->assertNotified();

            $salesRepresentative = $salesRepresentative->refresh();
            expect($salesRepresentative->cpf)->toBe($salesRepresentativeUpdateData->cpf)
                ->and($salesRepresentative->name)->toBe($salesRepresentativeUpdateData->name)
                ->and($salesRepresentative->email)->toBe($salesRepresentativeUpdateData->email);

        });

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
