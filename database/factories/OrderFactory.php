<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Client;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\SalesRepresentative;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // /** @var OrderStatusEnum $status */
        // $status = fake()->randomElement(OrderStatusEnum::cases());

        $distributor = Distributor::factory();

        return [
            'status' => OrderStatusEnum::PENDING, // $status->value,
            'notes' => fake()->optional()->sentence(),
            'distributor_id' => $distributor,
            'client_id' => Client::factory()->for($distributor),
            'sales_representative_id' => SalesRepresentative::factory()->for($distributor),
            'payment_method_id' => PaymentMethod::factory()->for($distributor),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => OrderStatusEnum::COMPLETED]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => OrderStatusEnum::CANCELLED]);
    }
}
