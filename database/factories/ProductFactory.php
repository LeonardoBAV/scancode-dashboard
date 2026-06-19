<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Distributor;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'distributor_id' => Distributor::factory(),
            'sku' => fake()->unique()->numerify('SKU-######'),
            'barcode' => fake()->unique()->ean13(),
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 1, 9999),
            'product_category_id' => ProductCategory::factory(),
        ];
    }
}
