<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\SalesRepresentative;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user instanceof SalesRepresentative && $user->distributor_id !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var Order $order */
        $order = $this->route('order');
        $distributorId = $order->distributor_id;

        return [
            'client_id' => [
                'sometimes',
                'integer',
                Rule::exists('clients', 'id')
                    ->where('distributor_id', $distributorId),
            ],
            'payment_method_id' => [
                'sometimes',
                'nullable',
                'integer',
                Rule::exists('payment_methods', 'id')
                    ->where('distributor_id', $distributorId),
            ],
            'status' => [
                'required',
                Rule::enum(OrderStatusEnum::class)->only([
                    OrderStatusEnum::COMPLETED,
                    OrderStatusEnum::CANCELLED,
                ]),
            ],
            'notes' => ['sometimes', 'nullable', 'string', 'max:65535'],
            'buyer_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'buyer_phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'order_items' => ['sometimes', 'array'],
            'order_items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('products', 'id')
                    ->where('distributor_id', $distributorId),
            ],
            'order_items.*.movement' => ['nullable', 'uuid'],
            'order_items.*.price' => ['required', 'numeric', 'min:0'],
            'order_items.*.qty' => ['required', 'integer', 'min:1'],
            'order_items.*.notes' => ['nullable', 'string', 'max:65535'],
        ];
    }
}
