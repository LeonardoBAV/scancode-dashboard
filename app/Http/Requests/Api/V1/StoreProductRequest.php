<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Models\SalesRepresentative;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'product_category_id' => [
                'nullable',
                'integer',
                Rule::exists('product_categories', 'id')
                    ->where('distributor_id', $seller->distributor_id),
            ],
        ];
    }
}
