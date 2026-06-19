<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\SalesRepresentatives\Pages\ViewSalesRepresentative;
use App\Models\SalesRepresentative;
use Illuminate\Support\Arr;

describe('SalesRepresentative View', function (): void {

    beforeEach(function (): void {
        SalesRepresentative::factory()->for($this->distributor)->create();
    });

    it('can load the page', function (): void {

        $salesRepresentative = SalesRepresentative::firstOrFail();
        $salesRepresentativeData = Arr::except($salesRepresentative->toArray(), ['id', 'created_at', 'updated_at', 'password', 'distributor_id']);

        $this->livewireTenant(ViewSalesRepresentative::class, ['record' => $salesRepresentative->getRouteKey()])
            ->assertOk()
            ->assertSchemaStateSet($salesRepresentativeData);

    });

});
