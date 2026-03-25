<?php

declare(strict_types=1);

use App\Filament\Dashboard\Pages\Tenancy\EditDistributorProfile;
use App\Models\Distributor;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

describe('Edit distributor profile', function (): void {

    it('can load the page', function (): void {
        livewire(EditDistributorProfile::class)
            ->assertOk();
    });

    it('shows the distributor name in the form', function (): void {
        livewire(EditDistributorProfile::class)
            ->assertSchemaExists('form')
            ->assertSchemaStateSet([
                'name' => Auth::user()->distributor->name,
            ]);
    });

    it('can update the distributor name', function (): void {
        $newName = 'Distribuidora Atualizada';
        $distributor = Auth::user()->distributor;
        expect($distributor)->not->toBeNull();

        livewire(EditDistributorProfile::class)
            ->fillForm(['name' => $newName])
            ->call('save')
            ->assertNotified()
            ->assertHasNoFormErrors();

        assertDatabaseHas(Distributor::class, [
            'id' => $distributor->id,
            'name' => $newName,
            'slug' => $distributor->slug,
        ]);
    });

    it('returns not found when the user cannot update the current tenant', function (): void {
        $otherDistributor = Distributor::factory()->create();
        Filament::setTenant($otherDistributor);

        livewire(EditDistributorProfile::class)
            ->assertNotFound();
    });
});
