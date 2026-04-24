<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->resource->getAttributes();

        return [
            'id' => $this->id,
            'sku' => $this->when(array_key_exists('sku', $attributes), $this->sku),
            'barcode' => $this->when(array_key_exists('barcode', $attributes), $this->barcode),
            'name' => $this->when(array_key_exists('name', $attributes), $this->name),
            'price' => $this->when(
                array_key_exists('price', $attributes),
                fn () => $this->price !== null ? (string) $this->price : null,
            ),
            'product_category_id' => $this->when(array_key_exists('product_category_id', $attributes), $this->product_category_id),
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
            'product_category' => $this->whenLoaded('productCategory', fn (): ProductCategoryResource => ProductCategoryResource::make($this->productCategory)),
            'order_items' => $this->whenLoaded('orderItems', fn (): array => $this->orderItems
                ->map(fn ($item): array => [
                    'id' => $item->id,
                    'order_id' => $item->order_id,
                    'product_id' => $item->product_id,
                    'price' => $item->price !== null ? (string) $item->price : null,
                    'qty' => $item->qty,
                ])
                ->values()
                ->all()),
        ];
    }
}
