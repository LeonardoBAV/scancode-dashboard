<?php

declare(strict_types=1);

use App\Filament\Admin\Resources\Distributors\Pages\ListDistributors;
use App\Models\Distributor;
use App\Models\Staff;
use Filament\Actions\Testing\TestAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    $this->staff = Staff::factory()->create();
    actingAs($this->staff, 'staff');

    Filament::setCurrentPanel('admin');
    Filament::bootCurrentPanel();
});

it('loads the distributors list page', function (): void {
    Distributor::factory()->count(2)->create();

    livewire(ListDistributors::class)
        ->assertOk()
        ->assertCanSeeTableRecords(Distributor::all());
});

it('opens the distributor view in a slide over from the table', function (): void {
    $distributor = Distributor::factory()->create(['name' => 'Distribuidor Teste']);

    $livewire = livewire(ListDistributors::class);

    assertTableRecordActionVisibility($livewire, $distributor, ViewAction::class, true);

    $livewire
        ->mountAction(TestAction::make(ViewAction::class)->table($distributor))
        ->assertOk()
        ->assertSee('Distribuidor Teste');
});

it('toggles distributor is_active from the table', function (): void {
    $distributor = Distributor::factory()->create(['is_active' => false]);

    livewire(ListDistributors::class)
        ->call('updateTableColumnState', 'is_active', $distributor->getKey(), true)
        ->assertHasNoErrors();

    expect($distributor->fresh()->is_active)->toBeTrue();
});
