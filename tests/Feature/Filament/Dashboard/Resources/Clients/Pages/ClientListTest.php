<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Clients\Pages\ListClients;
use App\Models\Client;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Livewire\livewire;

describe('Client List', function (): void {

    beforeEach(function (): void {
        Client::factory()->count(10)->create();
    });

    it('can load the page', function (): void {

        livewire(ListClients::class)
            ->assertSuccessful();
    });

    it('can list clients', function (): void {

        // obs: if start with filter by default test record that no fit in this default with ->assertCanNotSeeTableRecords(...)
        livewire(ListClients::class)
            ->assertCanSeeTableRecords(Client::all())
            ->assertCountTableRecords(Client::count());
    });

    describe('Table:', function (): void {

        it('can render columns', function (): void {
            livewire(ListClients::class)
                ->assertCanRenderTableColumn('cpf_cnpj')
                ->assertCanRenderTableColumn('corporate_name')
                ->assertCanRenderTableColumn('fantasy_name')
                ->assertCanRenderTableColumn('email')
                ->assertCanRenderTableColumn('phone')
                ->assertCanNotRenderTableColumn('created_at')
                ->assertCanNotRenderTableColumn('updated_at')

                ->toggleAllTableColumns()
                ->assertCanRenderTableColumn('created_at')
                ->assertCanRenderTableColumn('updated_at');
        });

        describe('Searchable:', function (): void {

            it('search is working', function (string $field): void {
                $client = Client::firstOrFail();
                $clientNotFound = Client::where('id', '!=', $client->id)->first(); // obs: remove

                $value = $client->getAttribute($field);
                $searchValue = is_string($value) ? $value : null;

                livewire(ListClients::class)
                    ->searchTable($searchValue)
                    ->assertCanSeeTableRecords([$client])
                    ->assertCanNotSeeTableRecords([$clientNotFound]);
            })->with('client_searchable_columns');

        });

        describe('Bulk Actions:', function (): void {

            it('can bulk delete clients', function (): void {

                livewire(ListClients::class)
                    ->assertCanSeeTableRecords(Client::all())
                    ->selectTableRecords(Client::all())
                    ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
                    ->assertNotified()
                    ->assertCanNotSeeTableRecords(Client::all());
            });

        });

    });

});
