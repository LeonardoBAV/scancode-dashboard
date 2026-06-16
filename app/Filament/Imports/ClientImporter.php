<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Client;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class ClientImporter extends Importer
{
    protected static ?string $model = Client::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('cpf_cnpj')
                ->requiredMapping()
                ->rules(fn (array $options): array => [
                    'required',
                    'max:255',
                    Rule::unique('clients', 'cpf_cnpj')
                        ->where('distributor_id', $options['distributor_id']),
                ]),
            ImportColumn::make('corporate_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('fantasy_name')
                ->rules(['max:255']),
            ImportColumn::make('email')
                ->rules(['email', 'max:255']),
            ImportColumn::make('phone')
                ->rules(['max:255']),
            ImportColumn::make('carrier')
                ->rules(['max:255']),
            ImportColumn::make('buyer_name')
                ->rules(['max:255']),
            ImportColumn::make('buyer_contact')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): Client
    {
        return new Client([
            'distributor_id' => $this->getOptions()['distributor_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your client import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
