<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Files\Pages;

use App\Filament\Admin\Resources\Files\FileResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFile extends CreateRecord
{
    protected static string $resource = FileResource::class;
}
