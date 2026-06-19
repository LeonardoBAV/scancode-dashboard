<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['path', 'description', 'file_type_id'])]
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
            'file_type_id' => 'integer',
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

    /**
     * @return BelongsTo<FileType, $this>
     */
    public function fileType(): BelongsTo
    {
        return $this->belongsTo(FileType::class);
    }

    public function getPublicUrl(): ?string
    {
        if ($this->path === null || $this->path === '') {
            return null;
        }

        return Storage::disk('public')->url($this->path);
    }
}
