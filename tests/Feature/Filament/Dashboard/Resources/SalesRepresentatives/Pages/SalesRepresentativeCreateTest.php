<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\CreateSalesRepresentative;
use App\Models\SalesRepresentative;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('SalesRepresentative Create', function (): void {

    it('can load the page', function (): void {
        $this->livewireTenant(CreateSalesRepresentative::class)
            ->assertOk();
    });

    it('has a form', function (): void {
        $this->livewireTenant(CreateSalesRepresentative::class)
            ->assertSchemaExists('form');
    });

    describe('Form', function (): void {

        it('has all fields', function (): void {

            $this->livewireTenant(CreateSalesRepresentative::class)
                ->assertFormFieldExists('cpf')
                ->assertFormFieldExists('name')
                ->assertFormFieldExists('email')
                ->assertFormFieldExists('password');

        });

        describe('Validation', function (): void {

            it('basic validations are working', function (SalesRepresentative $salesRepresentative, array $errors): void {

                $this->livewireTenant(CreateSalesRepresentative::class)
                    ->fillForm($salesRepresentative->toArray())
                    ->call('create')
                    ->assertHasFormErrors($errors)
                    ->assertNotNotified()
                    ->assertNoRedirect();

            })->with('sales_representative_validations');

            it('cpf unique validation is working', function (): void {

                $salesRepresentativeOne = SalesRepresentative::factory()->for($this->distributor)->create(['cpf' => '12345678901']);
                $salesRepresentativeTwo = SalesRepresentative::factory()->for($this->distributor)->make(['cpf' => $salesRepresentativeOne->cpf]);

                $this->livewireTenant(CreateSalesRepresentative::class)
                    ->fillForm($salesRepresentativeTwo->toArray())
                    ->call('create')
                    ->assertHasFormErrors(['cpf'])
                    ->assertNotNotified()
                    ->assertNoRedirect();

            });

        });

    });

    describe('Actions', function (): void {

        it('can create a sales representative', function (SalesRepresentative $salesRepresentative): void {
            $data = $salesRepresentative->withoutRelations()->toArray();

            livewire(CreateSalesRepresentative::class)
                ->fillForm([...$data, 'password' => 'password'])
                ->call('create')
                ->assertHasNoFormErrors()
                ->assertNotified()
                ->assertRedirect();

            assertDatabaseHas(SalesRepresentative::class, [...$data, 'distributor_id' => Auth::user()->distributor_id]);

        })->with('sales_representative_make_five_sales_representatives');

    });

});
