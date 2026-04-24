<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->resource->getAttributes();

        return [
            'id' => $this->id,
            'distributor_id' => $this->when(array_key_exists('distributor_id', $attributes), $this->distributor_id),
            'event_id' => $this->when(array_key_exists('event_id', $attributes), $this->event_id),
            'status' => $this->when(
                array_key_exists('status', $attributes),
                fn (): string => $this->status->value,
            ),
            'notes' => $this->when(array_key_exists('notes', $attributes), $this->notes),
            'client_id' => $this->when(array_key_exists('client_id', $attributes), $this->client_id),
            'sales_representative_id' => $this->when(array_key_exists('sales_representative_id', $attributes), $this->sales_representative_id),
            'payment_method_id' => $this->when(array_key_exists('payment_method_id', $attributes), $this->payment_method_id),
            'created_at' => $this->when(
                array_key_exists('created_at', $attributes),
                fn () => $this->created_at?->toIso8601String(),
            ),
            'updated_at' => $this->when(
                array_key_exists('updated_at', $attributes),
                fn () => $this->updated_at?->toIso8601String(),
            ),
            'order_items' => $this->whenLoaded(
                'orderItems',
                fn (): AnonymousResourceCollection => OrderItemResource::collection($this->orderItems),
            ),
        ];
    }
}
