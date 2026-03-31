<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Distributor;
use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+'.fake()->numberBetween(1, 14).' days');

        return [
            'distributor_id' => Distributor::factory(),
            'name' => fake()->sentence(3),
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d'),
        ];
    }
}
