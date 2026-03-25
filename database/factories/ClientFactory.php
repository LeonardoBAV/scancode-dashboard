<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Client;
use App\Models\Distributor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'distributor_id' => Distributor::factory(),
            'cpf_cnpj' => fake()->numerify('###########'),
            'corporate_name' => fake()->company(),
            'fantasy_name' => fake()->companySuffix().' '.fake()->word(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->regexify('\([0-9]{2}\) [0-9]{5}-[0-9]{4}'),
            'carrier' => fake()->optional()->company(),
        ];
    }

    /**
     * Indicate that the client is a natural person (CPF).
     */
    public function naturalPerson(): static
    {
        return $this->state(fn (array $attributes) => [
            'cpf_cnpj' => fake()->numerify('###########'),
        ]);
    }

    /**
     * Indicate that the client is a legal person (CNPJ).
     */
    public function legalPerson(): static
    {
        return $this->state(fn (array $attributes) => [
            'cpf_cnpj' => fake()->numerify('##############'),
        ]);
    }

    /**
     * Indicate that the client has no optional fields.
     */
    public function minimal(): static
    {
        return $this->state(fn (array $attributes) => [
            'corporate_name' => null,
            'fantasy_name' => null,
            'email' => null,
            'phone' => null,
        ]);
    }
}
