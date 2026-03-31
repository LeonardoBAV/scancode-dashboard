<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Distributor;
use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Distributor::query()->cursor() as $distributor) {
            Event::factory()->count(3)->for($distributor)->create();
        }
    }
}
