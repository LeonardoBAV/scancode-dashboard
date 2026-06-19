<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FileTypeEnum;
use Database\Factories\FileFactory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Number;

#[Fillable(['path', 'description', 'type'])]
class File extends Model
{
    public const DISK = 'files';

    /** @use HasFactory<FileFactory> */
    use HasFactory;

    /**
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
                $file->storage()->delete($file->path);
            }
        });
    }

    public function storage(): Filesystem
    {
        return Storage::disk(self::DISK);
    }

    public function getDownloadUrl(): ?string
    {
        if (! $this->isDownloadable()) {
            return null;
        }

        return route('admin.files.download', $this);
    }

    public function getDashboardDownloadUrl(): ?string
    {
        if (! $this->isDownloadable()) {
            return null;
        }

        return route('dashboard.files.download', $this);
    }

    public function isDownloadable(): bool
    {
        return $this->existsOnDisk();
    }

    public function displayName(): string
    {
        if (filled($this->description)) {
            return $this->description;
        }

        return $this->type->label();
    }

    public function fileName(): ?string
    {
        if (! filled($this->path)) {
            return null;
        }

        return basename($this->path);
    }

    public function formattedSize(): ?string
    {
        if (! $this->isDownloadable()) {
            return null;
        }

        return Number::fileSize($this->storage()->size($this->path));
    }

    public function existsOnDisk(): bool
    {
        return filled($this->path) && $this->storage()->exists($this->path);
    }
}
