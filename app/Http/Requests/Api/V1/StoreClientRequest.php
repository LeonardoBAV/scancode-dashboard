<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Models\SalesRepresentative;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user instanceof SalesRepresentative && $user->distributor_id !== null;
    }

    protected function prepareForValidation(): void
    {
        /** @var SalesRepresentative $seller */
        $seller = $this->user();

        $this->merge([
            'distributor_id' => $seller->distributor_id,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var SalesRepresentative $seller */
        $seller = $this->user();

        return [
            'distributor_id' => [
                'required',
                'integer',
                Rule::in([(int) $seller->distributor_id]),
            ],
            'cpf_cnpj' => [
                'required',
                'string',
                'max:18',
                Rule::unique('clients', 'cpf_cnpj')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'corporate_name' => ['required', 'string', 'max:255'],
            'fantasy_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'carrier' => ['nullable', 'string', 'max:255'],
        ];
    }
}
