<?php

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Pages;

use App\Filament\Dashboard\Resources\SalesRepresentatives\SalesRepresentativeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSalesRepresentative extends EditRecord
{
    protected static string $resource = SalesRepresentativeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
