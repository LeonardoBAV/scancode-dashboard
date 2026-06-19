<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Distributor;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $distributor = Distributor::factory();

        return [
            'distributor_id' => $distributor,
            'order_id' => Order::factory()->for($distributor),
            'product_id' => Product::factory()->state([
                'product_category_id' => ProductCategory::factory()->for($distributor),
            ]),
            'price' => fake()->randomFloat(2, 10, 1000),
            'qty' => fake()->numberBetween(1, 10),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
