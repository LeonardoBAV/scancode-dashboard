<?php

declare(strict_types=1);

namespace App\Utils;

class QueryHelper
{
    /**
     * @param  list<string>  $allowedColumns
     * @return array{0: string, 1: string}
     */
    public static function parseOrder(
        string $order,
        array $allowedColumns,
        string $defaultColumn = 'id',
        string $defaultDirection = 'asc',
    ): array {
        $parts = explode(':', $order, 2);
        $column = $parts[0] ?? $defaultColumn;
        $direction = strtolower($parts[1] ?? $defaultDirection);

        if (! in_array($column, $allowedColumns, true)) {
            $column = $defaultColumn;
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = $defaultDirection;
        }

        return [$column, $direction];
    }
}
