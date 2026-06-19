<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FileTypeEnum;
use Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable(['path', 'description', 'type'])]
class File extends Model
{
    /** @use HasFactory<FileFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => FileTypeEnum::class,
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (File $file): void {
            if (filled($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
        });
    }

    public function getDownloadUrl(): ?string
    {
        if (! filled($this->path)) {
            return null;
        }

        return route('admin.files.download', $this);
    }

    public function existsOnDisk(): bool
    {
        return filled($this->path) && Storage::disk('public')->exists($this->path);
    }

    public function getPublicUrl(): ?string
    {
        if ($this->path === null || $this->path === '') {
            return null;
        }

        return Storage::disk('public')->url($this->path);
    }
}
