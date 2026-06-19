<?php

declare(strict_types=1);

use App\Enums\FileTypeEnum;
use App\Filament\Admin\Resources\Files\Pages\CreateFile;
use App\Models\File;
use App\Models\Staff;
use Filament\Facades\Filament;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Storage::fake(File::DISK);

    $this->staff = Staff::factory()->create();
    actingAs($this->staff, 'staff');

    Filament::setCurrentPanel('admin');
    Filament::bootCurrentPanel();
});

it('creates a file with upload', function (): void {
    $upload = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    livewire(CreateFile::class)
        ->fillForm([
            'path' => $upload,
            'description' => 'Test file',
            'type' => FileTypeEnum::APP->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified()
        ->assertRedirect();

    $file = File::query()->firstOrFail();

    expect($file->description)->toBe('Test file')
        ->and($file->type)->toBe(FileTypeEnum::APP)
        ->and($file->path)->toEndWith('.pdf')
        ->and($file->storage()->exists($file->path))->toBeTrue();
});
