<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\CreateSalesRepresentative;
use App\Models\SalesRepresentative;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('SalesRepresentative Create', function (): void {

    it('can load the page', function (): void {
        livewire(CreateSalesRepresentative::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        livewire(CreateSalesRepresentative::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            livewire(CreateSalesRepresentative::class)
                ->assertFormFieldExists('cpf')
                ->assertFormFieldExists('name')
                ->assertFormFieldExists('email')
                ->assertFormFieldExists('password');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (SalesRepresentative $salesRepresentative, array $errors): void {

                livewire(CreateSalesRepresentative::class)
                    ->fillForm($salesRepresentative->toArray())
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('sales_representative_validations');

            it('cpf unique validation is working', function (): void {

                $salesRepresentativeOne = SalesRepresentative::factory()->create(['cpf' => '12345678901']);
                $salesRepresentativeTwo = SalesRepresentative::factory()->make(['cpf' => $salesRepresentativeOne->cpf]);

                livewire(CreateSalesRepresentative::class)
                    ->fillForm($salesRepresentativeTwo->toArray())
                    ->call('create')
                    ->assertHasFormErrors(['cpf' => 'unique'])
                    ->assertNotNotified()
                    ->assertNoRedirect();

            });

        });

    });

    describe('Actions', function (): void {

        it('can create a sales representative', function (SalesRepresentative $salesRepresentative): void {
            livewire(CreateSalesRepresentative::class)
                ->fillForm($salesRepresentative->toArray())
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(SalesRepresentative::class, $salesRepresentative->toArray());
        })->with('sales_representative_make_five_sales_representatives');

    });

});
