<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Distributor;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

abstract class TestCase extends BaseTestCase
{
    protected Distributor $distributor;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->distributor = Distributor::factory()->create();
        $this->user = User::factory()->for($this->distributor)->create();

        $this->actingAs($this->user);

        Filament::setCurrentPanel('dashboard');
        Filament::setTenant($this->distributor);
        Filament::bootCurrentPanel();
    }

    /**
     * @param  class-string  $component
     * @param  array<string, mixed>  $parameters
     */
    protected function livewireTenant(string $component, array $parameters = []): Testable
    {
        return Livewire::test($component, array_merge([
            'tenant' => $this->distributor->getRouteKey(),
        ], $parameters));
    }
}
