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
            'Eletrônicos',
            'Roupas',
            'Alimentos',
            'Bebidas',
            'Móveis',
            'Ferramentas',
            'Cosméticos',
            'Papelaria',
            'Brinquedos',
            'Esportes',
        ];

        foreach ($categories as $category) {
            ProductCategory::factory()->create([
                'name' => $category,
            ]);
        }
    }
}

