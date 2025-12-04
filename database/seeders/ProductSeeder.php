<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ProductCategory::all();

        $categories->each(function (ProductCategory $category): void {
            Product::factory()
                ->count(10)
                ->create([
                    'product_category_id' => $category->id,
                ]);
        });
    }
}

