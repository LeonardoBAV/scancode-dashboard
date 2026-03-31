<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\OrderStatusEnum;
use App\Models\Client;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\SalesRepresentative;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();
        $products = Product::all();

        $businessHours = range(8, 18);

        for ($daysAgo = 3; $daysAgo >= 0; $daysAgo--) {
            $date = now()->subDays($daysAgo);

            foreach ($businessHours as $hour) {
                $ordersInThisHour = fake()->numberBetween(2, 20);

                for ($i = 0; $i < $ordersInThisHour; $i++) {
                    $orderDateTime = $date->copy()
                        ->setHour($hour)
                        ->setMinute(fake()->numberBetween(0, 59))
                        ->setSecond(fake()->numberBetween(0, 59));

                    $statusRandom = fake()->numberBetween(1, 100);
                    if ($statusRandom <= 80) {
                        $targetStatus = OrderStatusEnum::COMPLETED;
                    } elseif ($statusRandom <= 95) {
                        $targetStatus = OrderStatusEnum::PENDING;
                    } else {
                        $targetStatus = OrderStatusEnum::CANCELLED;
                    }

                    $client = $clients->random();
                    $distributorId = $client->distributor_id;

                    $salesRepresentative = SalesRepresentative::query()
                        ->where('distributor_id', $distributorId)
                        ->inRandomOrder()
                        ->first()
                        ?? SalesRepresentative::factory()->create(['distributor_id' => $distributorId]);

                    $paymentMethod = PaymentMethod::query()
                        ->where('distributor_id', $distributorId)
                        ->inRandomOrder()
                        ->first()
                        ?? PaymentMethod::factory()->create(['distributor_id' => $distributorId]);

                    $event = Event::query()
                        ->where('distributor_id', $distributorId)
                        ->inRandomOrder()
                        ->first()
                        ?? Event::factory()->create(['distributor_id' => $distributorId]);

                    $order = Order::create([
                        'distributor_id' => $distributorId,
                        'event_id' => $event->id,
                        'status' => OrderStatusEnum::PENDING,
                        'notes' => fake()->optional(0.3)->sentence(),
                        'client_id' => $client->id,
                        'sales_representative_id' => $salesRepresentative->id,
                        'payment_method_id' => $paymentMethod->id,
                        'created_at' => $orderDateTime,
                        'updated_at' => $orderDateTime,
                    ]);

                    $itemsCount = fake()->numberBetween(5, 20);

                    for ($j = 0; $j < $itemsCount; $j++) {
                        $tenantProducts = $products->where('distributor_id', $distributorId);
                        $product = $tenantProducts->isNotEmpty()
                            ? $tenantProducts->random()
                            : $products->random();

                        OrderItem::create([
                            'distributor_id' => $distributorId,
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'price' => $product->price,
                            'qty' => fake()->numberBetween(1, 10),
                            'notes' => fake()->optional(0.2)->sentence(),
                            'created_at' => $orderDateTime,
                            'updated_at' => $orderDateTime,
                        ]);
                    }

                    if ($targetStatus === OrderStatusEnum::COMPLETED) {
                        $order->toComplete();
                    } elseif ($targetStatus === OrderStatusEnum::CANCELLED) {
                        $order->toCancel();
                    }
                }
            }

            $this->command->info("✅ Pedidos criados para {$date->format('d/m/Y')}");
        }

        $totalOrders = Order::count();
        $totalItems = OrderItem::count();

        $this->command->info('🎉 Seeding concluído!');
        $this->command->info("📦 Total de pedidos: {$totalOrders}");
        $this->command->info("🛒 Total de itens: {$totalItems}");
    }
}
