<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FileTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class FileType extends Model
{
    /** @use HasFactory<FileTypeFactory> */
    use HasFactory;

    /**
     * @return HasMany<File, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
