<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\PaymentMethod;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class PaymentMethodImporter extends Importer
{
    protected static ?string $model = PaymentMethod::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(fn (array $options): array => [
                    'required',
                    'max:255',
                    Rule::unique('payment_methods', 'name')
                        ->where('distributor_id', $options['distributor_id']),
                ]),
        ];
    }

    public function resolveRecord(): PaymentMethod
    {
        return new PaymentMethod([
            'distributor_id' => $this->getOptions()['distributor_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your payment method import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
