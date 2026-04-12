<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        $product = $this->route('product');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'sku' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'sku')
                    ->where('distributor_id', $product->distributor_id)
                    ->ignore($product->id),
            ],
            'barcode' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')
                    ->where('distributor_id', $product->distributor_id)
                    ->ignore($product->id),
            ],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'product_category_id' => ['sometimes', 'nullable', 'integer', 'exists:product_categories,id'],
        ];
    }
}
