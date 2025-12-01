<?php

declare(strict_types=1);

use App\Models\Client;
use Illuminate\Support\Facades\Schema;

describe('Client model:', function (): void {

    test('check fillable attributes and protected attributes', function (array $protected_columns): void {
        $table_columns = Schema::getColumnListing((new Client)->getTable());
        $model_fillables = (new Client)->getFillable();

        $expected_in_fillables = array_diff($table_columns, $protected_columns);

        $attributes_missing = array_diff($expected_in_fillables, $model_fillables);
        $attributes_overfilled = array_intersect($model_fillables, $protected_columns);

        expect($attributes_missing)->toBeEmpty('ERRO: Some columns are missing in the fillable array: '.implode(', ', $attributes_missing));
        expect($attributes_overfilled)->toBeEmpty('ERRO: The following attibutes cannot be included in the fillable array: '.implode(', ', $attributes_overfilled));

    })->with('protected_columns');

});
