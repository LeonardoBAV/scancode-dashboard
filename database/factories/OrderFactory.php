<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Client;
use App\Models\Distributor;
use App\Models\Event;
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

    public function configure(): static
    {
        return $this->afterMaking(function (Order $order): void {
            if ($order->distributor_id === null) {
                return;
            }

            $event = $order->event_id ? Event::query()->find($order->event_id) : null;
            $eventValid = $event !== null
                && (int) $event->distributor_id === (int) $order->distributor_id;

            if (! $eventValid) {
                $order->event_id = Event::factory()->create([
                    'distributor_id' => $order->distributor_id,
                ])->id;
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $distributor = Distributor::factory();

        return [
            'status' => OrderStatusEnum::PENDING,
            'notes' => fake()->optional()->sentence(),
            'buyer_name' => fake()->optional()->name(),
            // Must match Filament `TextInput::make('buyer_phone')->tel()` validation format.
            'buyer_phone' => fake()->optional()->regexify('\([0-9]{2}\) [0-9]{5}-[0-9]{4}'),
            'distributor_id' => $distributor,
            'event_id' => Event::factory()->for($distributor),
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
