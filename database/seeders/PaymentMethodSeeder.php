<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Distributor;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            ['name' => 'PIX'],
            ['name' => 'Cash'],
            ['name' => 'Credit Card'],
            ['name' => 'Debit Card'],
            ['name' => 'Bank Transfer'],
        ];

        foreach (Distributor::query()->cursor() as $distributor) {
            foreach ($paymentMethods as $method) {
                PaymentMethod::create([
                    ...$method,
                    'distributor_id' => $distributor->id,
                ]);
            }
        }
    }
}
