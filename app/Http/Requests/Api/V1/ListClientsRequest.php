<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ListClientsRequest extends FormRequest
{
    private const array ORDERABLE_COLUMNS = [
        'id',
        'cpf_cnpj',
        'corporate_name',
        'fantasy_name',
        'email',
        'phone',
        'carrier',
        'created_at',
        'updated_at',
    ];

    private const array SELECTABLE_FIELDS = [
        'id',
        'cpf_cnpj',
        'corporate_name',
        'fantasy_name',
        'email',
        'phone',
        'carrier',
        'created_at',
        'updated_at',
    ];

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
            'filter.corporate_name' => ['sometimes', 'string', 'max:255'],
            'filter.fantasy_name' => ['sometimes', 'string', 'max:255'],
            'filter.email' => ['sometimes', 'string', 'max:255'],
            'filter.cpf_cnpj' => ['sometimes', 'string', 'max:18'],
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
        $raw = $this->input('order', 'corporate_name:asc');

        if (! is_string($raw) || $raw === '') {
            return 'corporate_name:asc';
        }

        $parts = explode(':', $raw, 2);
        $column = $parts[0] ?? 'corporate_name';
        $direction = strtolower($parts[1] ?? 'asc');

        if (! in_array($column, self::ORDERABLE_COLUMNS, true)) {
            return 'corporate_name:asc';
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
