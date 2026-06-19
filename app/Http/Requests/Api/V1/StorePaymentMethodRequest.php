<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Models\SalesRepresentative;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentMethodRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_methods', 'name')
                    ->where('distributor_id', $seller->distributor_id),
            ],
        ];
    }
}
