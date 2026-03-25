<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Distributor;
use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesRepresentative>
 */
class SalesRepresentativeFactory extends Factory
{
    protected $model = SalesRepresentative::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'distributor_id' => Distributor::factory(),
            'cpf' => fake()->numerify('###########'),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
        ];
    }
}
