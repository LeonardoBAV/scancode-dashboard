<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Clothing',
            'Food',
            'Drinks',
            'Furniture',
            'Tools',
            'Cosmetics',
            'Stationery',
            'Toys',
            'Sports',
        ];

        foreach ($categories as $category) {
            ProductCategory::factory()->create([
                'name' => $category,
            ]);
        }
    }
}
