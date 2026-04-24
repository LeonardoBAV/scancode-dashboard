<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'start' => $this->when(
                array_key_exists('start', $attributes),
                fn () => $this->start?->format('Y-m-d'),
            ),
            'end' => $this->when(
                array_key_exists('end', $attributes),
                fn () => $this->end?->format('Y-m-d'),
            ),
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
            'orders' => $this->whenLoaded(
                'orders',
                fn (): AnonymousResourceCollection => OrderResource::collection($this->orders),
            ),
        ];
    }
}
