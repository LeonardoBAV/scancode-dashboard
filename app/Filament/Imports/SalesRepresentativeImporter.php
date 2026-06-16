<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\SalesRepresentative;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class SalesRepresentativeImporter extends Importer
{
    protected static ?string $model = SalesRepresentative::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('cpf')
                ->requiredMapping()
                ->rules([
                    'required',
                    'max:255',
                    Rule::unique('sales_representatives', 'cpf'),
                ]),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('password')
                ->requiredMapping()
                ->sensitive()
                ->rules(['required', 'max:255']),
        ];
    }

    public function resolveRecord(): SalesRepresentative
    {
        return new SalesRepresentative([
            'distributor_id' => $this->getOptions()['distributor_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your sales representative import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
