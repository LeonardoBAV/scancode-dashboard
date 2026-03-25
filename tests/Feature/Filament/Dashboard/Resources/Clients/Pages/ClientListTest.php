<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\Pages\ListClients;
use App\Models\Client;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

describe('Client List', function (): void {

    beforeEach(function (): void {
        Client::factory()->count(10)->create();
    });

    it('can load the page', function (): void {

        $this->livewireTenant(ListClients::class)
            ->assertSuccessful();
    });

    it('can list clients', function (): void {

        $this->livewireTenant(ListClients::class)
            ->assertCanSeeTableRecords(Client::all())
            ->assertCountTableRecords(Client::count());
    });

    describe('Table:', function (): void {

        it('can render columns', function (): void {
            $this->livewireTenant(ListClients::class)
                ->assertCanRenderTableColumn('cpf_cnpj')
                ->assertCanRenderTableColumn('corporate_name')
                ->assertCanRenderTableColumn('fantasy_name')
                ->assertCanRenderTableColumn('email')
                ->assertCanRenderTableColumn('phone')
                ->assertCanRenderTableColumn('carrier')
                ->assertCanNotRenderTableColumn('created_at')
                ->assertCanNotRenderTableColumn('updated_at')

                ->toggleAllTableColumns()
                ->assertCanRenderTableColumn('created_at')
                ->assertCanRenderTableColumn('updated_at');
        });

        describe('Searchable:', function (): void {

            it('search is working', function (callable $fnClient, callable $fnClientNotFound, callable $fnValue): void {
                /**
                 * @var callable():Client $fnClient
                 * @var callable(string):Client $fnClientNotFound
                 * @var callable(Client):string $fnValue
                 */
                $client = $fnClient();
                $searchValue = $fnValue($client);
                $clientNotFound = $fnClientNotFound($searchValue);

                $this->livewireTenant(ListClients::class)
                    ->assertCanSeeTableRecords(Client::all())
                    ->searchTable($searchValue)
                    ->assertCanSeeTableRecords([$client])
                    ->assertCanNotSeeTableRecords([$clientNotFound]);
            })->with('client_searchable_columns');

        });

        describe('Bulk Actions:', function (): void {

            it('can bulk delete clients', function (): void {

                $this->livewireTenant(ListClients::class)
                    ->assertCanSeeTableRecords(Client::all())
                    ->selectTableRecords(Client::all())
                    ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                    ->assertNotified()
                    ->assertCanNotSeeTableRecords(Client::all());
            });

        });

    });

});
