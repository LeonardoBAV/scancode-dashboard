<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'order_id' => $this->when(array_key_exists('order_id', $attributes), $this->order_id),
            'product_id' => $this->when(array_key_exists('product_id', $attributes), $this->product_id),
            'price' => $this->when(
                array_key_exists('price', $attributes),
                fn (): ?string => $this->price !== null ? (string) $this->price : null,
            ),
            'qty' => $this->when(array_key_exists('qty', $attributes), $this->qty),
            'notes' => $this->when(array_key_exists('notes', $attributes), $this->notes),
            'created_at' => $this->when(
                array_key_exists('created_at', $attributes),
                fn () => $this->created_at?->toIso8601String(),
            ),
            'updated_at' => $this->when(
                array_key_exists('updated_at', $attributes),
                fn () => $this->updated_at?->toIso8601String(),
            ),
        ];
    }
}
