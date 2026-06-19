<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentMethodRequest extends FormRequest
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
        $paymentMethod = $this->route('paymentMethod');

        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('payment_methods', 'name')
                    ->where('distributor_id', $paymentMethod->distributor_id)
                    ->ignore($paymentMethod->id),
            ],
        ];
    }
}
