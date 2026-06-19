<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FileTypeEnum;
use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => 'files/'.fake()->uuid().'.pdf',
            'description' => fake()->optional()->sentence(),
            'type' => fake()->randomElement(FileTypeEnum::cases()),
        ];
    }
}
