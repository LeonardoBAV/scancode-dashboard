<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $client = $this->route('client');

        return [
            'cpf_cnpj' => [
                'sometimes',
                'string',
                'max:18',
                Rule::unique('clients', 'cpf_cnpj')
                    ->where('distributor_id', $client->distributor_id)
                    ->ignore($client->id),
            ],
            'corporate_name' => ['sometimes', 'string', 'max:255'],
            'fantasy_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'carrier' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
