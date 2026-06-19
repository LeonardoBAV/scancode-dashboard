<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Distributors\Pages;

use App\Filament\Admin\Resources\Distributors\DistributorResource;
use Filament\Resources\Pages\ListRecords;

class ListDistributors extends ListRecords
{
    protected static string $resource = DistributorResource::class;
}
