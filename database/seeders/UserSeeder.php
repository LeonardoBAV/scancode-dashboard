<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->create();

        User::factory()->create([
            'name' => 'Sample',
            'email' => 'sample@sample.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
