<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->resource->getAttributes();

        return [
            'id' => $this->id,
            'cpf_cnpj' => $this->when(array_key_exists('cpf_cnpj', $attributes), $this->cpf_cnpj),
            'corporate_name' => $this->when(array_key_exists('corporate_name', $attributes), $this->corporate_name),
            'fantasy_name' => $this->when(array_key_exists('fantasy_name', $attributes), $this->fantasy_name),
            'email' => $this->when(array_key_exists('email', $attributes), $this->email),
            'phone' => $this->when(array_key_exists('phone', $attributes), $this->phone),
            'carrier' => $this->when(array_key_exists('carrier', $attributes), $this->carrier),
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
