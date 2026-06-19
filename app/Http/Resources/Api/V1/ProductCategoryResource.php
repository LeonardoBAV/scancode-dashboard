<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->resource->getAttributes();

        return [
            'id' => $this->id,
            'name' => $this->when(array_key_exists('name', $attributes), $this->name),
            'distributor_id' => $this->when(array_key_exists('distributor_id', $attributes), $this->distributor_id),
            'created_at' => $this->when(
                array_key_exists('created_at', $attributes),
                fn () => $this->created_at?->toIso8601String(),
            ),
            'updated_at' => $this->when(
                array_key_exists('updated_at', $attributes),
                fn () => $this->updated_at?->toIso8601String(),
            ),
            'distributor' => $this->whenLoaded('distributor', fn (): array => [
                'id' => $this->distributor->id,
                'name' => $this->distributor->name,
                'slug' => $this->distributor->slug,
            ]),
            'products' => $this->whenLoaded('products', fn (): array => $this->products
                ->map(fn ($product): array => [
                    'id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'price' => $product->price !== null ? (string) $product->price : null,
                ])
                ->values()
                ->all()),
        ];
    }
}
