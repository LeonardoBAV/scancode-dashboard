<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SalesRepresentative;
use Illuminate\Database\Seeder;

class SalesRepresentativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalesRepresentative::factory()->count(20)->create();
    }
}

