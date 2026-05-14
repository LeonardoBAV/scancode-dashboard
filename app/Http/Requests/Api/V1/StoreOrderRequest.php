<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Models\SalesRepresentative;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
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
            'sales_representative_id' => $seller->id,
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
            'sales_representative_id' => [
                'required',
                'integer',
                Rule::in([(int) $seller->id]),
            ],
            'event_id' => [
                'required',
                'integer',
                Rule::exists('events', 'id')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'client_id' => [
                'required',
                'integer',
                Rule::exists('clients', 'id')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'payment_method_id' => [
                'required',
                'integer',
                Rule::exists('payment_methods', 'id')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'notes' => ['nullable', 'string', 'max:65535'],
            'buyer_name' => ['nullable', 'string', 'max:255'],
            'buyer_phone' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'items.*.notes' => ['nullable', 'string', 'max:65535'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function orderData(): array
    {
        return $this->safe()->except('items');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function orderItems(): array
    {
        /** @var array<int, array<string, mixed>> $items */
        $items = $this->validated('items');

        return $items;
    }
}
