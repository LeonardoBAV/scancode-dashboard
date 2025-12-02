<?php

declare(strict_types=1);

use App\Models\SalesRepresentative;
use Illuminate\Support\Facades\Schema;

describe('SalesRepresentative model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protectedColumns): void {
        $tableColumns = Schema::getColumnListing((new SalesRepresentative)->getTable());
        $modelFillables = (new SalesRepresentative)->getFillable();

        $expectedInFillables = array_diff($tableColumns, $protectedColumns);

        $attributesMissing = array_diff($expectedInFillables, $modelFillables);
        $attributesOverfilled = array_intersect($modelFillables, $protectedColumns);

        expect($attributesMissing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributesMissing));
        expect($attributesOverfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributesOverfilled));

    })->with('sales_representative_protected_columns');

});
