<?php

declare(strict_types=1);

namespace App\Filament\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('sku')
                ->label('SKU')
                ->requiredMapping()
                ->rules(fn (array $options): array => [
                    'required',
                    'max:255',
                    Rule::unique('products', 'sku')
                        ->where('distributor_id', $options['distributor_id']),
                ]),
            ImportColumn::make('barcode')
                ->rules(fn (array $options): array => [
                    'nullable',
                    'max:255',
                    Rule::unique('products', 'barcode')
                        ->where('distributor_id', $options['distributor_id']),
                ]),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('price')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'numeric']),
            ImportColumn::make('productCategory')
                ->label(__('resources.product.form.product_category_name'))
                ->relationship(
                    resolveUsing: fn (mixed $state, array $options): ?ProductCategory => ProductCategory::query()
                        ->where('distributor_id', $options['distributor_id'])
                        ->where('name', $state)
                        ->first(),
                ),
        ];
    }

    public function resolveRecord(): Product
    {
        return new Product([
            'distributor_id' => $this->getOptions()['distributor_id'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your product import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
