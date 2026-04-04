<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ListEventsRequest extends FormRequest
{
    private const array ORDERABLE_COLUMNS = ['id', 'name', 'start', 'end', 'created_at', 'updated_at'];

    private const array SELECTABLE_FIELDS = ['id', 'name', 'start', 'end', 'created_at', 'updated_at'];

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'filter' => ['sometimes', 'array'],
            'filter.name' => ['sometimes', 'string', 'max:255'],
            'filter.start_from' => ['sometimes', 'date'],
            'filter.start_to' => ['sometimes', 'date'],
            'filter.end_from' => ['sometimes', 'date'],
            'filter.end_to' => ['sometimes', 'date'],
            'fields' => ['sometimes', 'string'],
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

    public function order(): string
    {
        $raw = $this->input('order', 'start:asc');

        if (! is_string($raw) || $raw === '') {
            return 'start:asc';
        }

        $parts = explode(':', $raw, 2);
        $column = $parts[0] ?? 'start';
        $direction = strtolower($parts[1] ?? 'asc');

        if (! in_array($column, self::ORDERABLE_COLUMNS, true)) {
            return 'start:asc';
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
