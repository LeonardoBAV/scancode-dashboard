<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Client;
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

        return [
            'status' => OrderStatusEnum::PENDING, // $status->value,
            'notes' => fake()->optional()->sentence(),
            'client_id' => Client::factory(),
            'sales_representative_id' => SalesRepresentative::factory(),
            'payment_method_id' => PaymentMethod::factory(),
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
