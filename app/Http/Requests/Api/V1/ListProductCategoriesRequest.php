<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListProductCategoriesRequest extends FormRequest
{
    private const array ORDERABLE_COLUMNS = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    private const array SELECTABLE_FIELDS = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];

    private const array ALLOWED_RELATIONS = [
        'distributor',
        'products',
    ];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'filter' => ['sometimes', 'array'],
            'filter.name' => ['sometimes', 'string', 'max:255'],
            'fields' => ['sometimes', 'string'],
            'relations' => ['sometimes', 'array'],
            'relations.*' => ['string', Rule::in(self::ALLOWED_RELATIONS)],
            'order' => ['sometimes', 'string'],
            'size' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function filters(): array
    {
        return $this->input('filter', []);
    }

    /**
     * @return list<string>
     */
    public function fields(): array
    {
        $raw = $this->input('fields', '');

        if (! is_string($raw) || $raw === '') {
            return [];
        }

        return array_values(
            array_intersect(
                array_map('trim', explode(',', $raw)),
                self::SELECTABLE_FIELDS,
            ),
        );
    }

    /**
     * @return list<string>
     */
    public function relations(): array
    {
        $raw = $this->input('relations', []);

        if (! is_array($raw)) {
            return [];
        }

        $names = array_map(
            static fn (mixed $item): string => is_string($item) ? trim($item) : '',
            $raw,
        );

        return array_values(array_unique(array_intersect($names, self::ALLOWED_RELATIONS)));
    }

    public function order(): string
    {
        $raw = $this->input('order', 'name:asc');

        if (! is_string($raw) || $raw === '') {
            return 'name:asc';
        }

        $parts = explode(':', $raw, 2);
        $column = $parts[0] ?? 'name';
        $direction = strtolower($parts[1] ?? 'asc');

        if (! in_array($column, self::ORDERABLE_COLUMNS, true)) {
            return 'name:asc';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        return "{$column}:{$direction}";
    }

    public function size(): ?int
    {
        $value = $this->input('size');

        return $value !== null ? (int) $value : null;
    }
}
