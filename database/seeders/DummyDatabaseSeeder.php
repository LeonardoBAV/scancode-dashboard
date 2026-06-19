<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application with dummy/demo data.
     */
    public function run(): void
    {
        $this->call([
            StaffSeeder::class,
            UserSeeder::class,
            PaymentMethodSeeder::class,
            ClientSeeder::class,
            SalesRepresentativeSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            EventSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
