<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();

        $orders->each(function (Order $order): void {
            OrderItem::factory()
                ->count(fake()->numberBetween(1, 5))
                ->create(['order_id' => $order->id]);
        });
    }
}
