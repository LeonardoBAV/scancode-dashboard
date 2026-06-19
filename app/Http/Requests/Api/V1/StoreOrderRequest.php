<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\OrderStatusEnum;
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
                'nullable',
                'integer',
                Rule::exists('payment_methods', 'id')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'status' => [
                'required',
                Rule::enum(OrderStatusEnum::class)->only([
                    OrderStatusEnum::COMPLETED,
                    OrderStatusEnum::CANCELLED,
                ]),
            ],
            'notes' => ['nullable', 'string', 'max:65535'],
            'buyer_name' => ['nullable', 'string', 'max:255'],
            'buyer_phone' => ['nullable', 'string', 'max:255'],
            'order_items' => ['nullable', 'array'],
            'order_items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('products', 'id')
                    ->where('distributor_id', $seller->distributor_id),
            ],
            'order_items.*.movement' => ['nullable', 'uuid'],
            'order_items.*.price' => ['required', 'numeric', 'min:0'],
            'order_items.*.qty' => ['required', 'integer', 'min:1'],
            'order_items.*.notes' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
