<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\FileType;
use Illuminate\Database\Seeder;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Documento', 'Imagem', 'Planilha', 'Outros'] as $name) {
            FileType::query()->updateOrCreate(
                ['name' => $name],
            );
        }
    }
}
