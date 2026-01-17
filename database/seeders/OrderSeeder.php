<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\OrderStatusEnum;
use App\Models\Client;
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
        // Buscar dados existentes
        $clients = Client::all();
        $salesRepresentatives = SalesRepresentative::all();
        $paymentMethods = PaymentMethod::all();
        $products = Product::all();

        $businessHours = range(8, 18);

        // Últimos 4 dias (incluindo hoje)
        for ($daysAgo = 3; $daysAgo >= 0; $daysAgo--) {
            $date = now()->subDays($daysAgo);

            // Para cada horário do dia
            foreach ($businessHours as $hour) {
                // Quantidade de pedidos neste horário (2 a 20)
                $ordersInThisHour = fake()->numberBetween(2, 20);

                for ($i = 0; $i < $ordersInThisHour; $i++) {
                    $orderDateTime = $date->copy()
                        ->setHour($hour)
                        ->setMinute(fake()->numberBetween(0, 59))
                        ->setSecond(fake()->numberBetween(0, 59));

                    // Determinar status do pedido (80% completed, 15% pending, 5% cancelled)
                    $statusRandom = fake()->numberBetween(1, 100);
                    if ($statusRandom <= 80) {
                        $status = OrderStatusEnum::COMPLETED;
                    } elseif ($statusRandom <= 95) {
                        $status = OrderStatusEnum::PENDING;
                    } else {
                        $status = OrderStatusEnum::CANCELLED;
                    }

                    // Criar o pedido
                    $order = Order::create([
                        'status' => $status,
                        'notes' => fake()->optional(0.3)->sentence(),
                        'client_id' => $clients->random()->id,
                        'sales_representative_id' => $salesRepresentatives->random()->id,
                        'payment_method_id' => $paymentMethods->random()->id,
                        'created_at' => $orderDateTime,
                        'updated_at' => $orderDateTime,
                    ]);

                    // Quantidade de itens neste pedido (5 a 20)
                    $itemsCount = fake()->numberBetween(5, 20);

                    // Criar os itens do pedido
                    for ($j = 0; $j < $itemsCount; $j++) {
                        $product = $products->random();

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'price' => $product->price, // Usar o preço do produto
                            'qty' => fake()->numberBetween(1, 10),
                            'notes' => fake()->optional(0.2)->sentence(),
                            'created_at' => $orderDateTime,
                            'updated_at' => $orderDateTime,
                        ]);
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
