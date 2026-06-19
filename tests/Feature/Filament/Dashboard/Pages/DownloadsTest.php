<?php

declare(strict_types=1);

use App\Enums\FileTypeEnum;
use App\Filament\Dashboard\Pages\Downloads;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

describe('Downloads page', function (): void {

    beforeEach(function (): void {
        Storage::fake(File::DISK);
    });

    it('can load the page', function (): void {
        livewire(Downloads::class)
            ->assertOk();
    });

    it('shows unavailable state when app and desktop files are missing', function (): void {
        livewire(Downloads::class)
            ->assertSee(__('filament.support.downloads_page.applications'))
            ->assertSee(__('enums.file_type.app'))
            ->assertSee(__('enums.file_type.desktop'))
            ->assertSee(__('filament.support.downloads_page.unavailable'))
            ->assertSee(__('filament.support.downloads_page.unavailable_hint'));
    });

    it('shows download links for available app and desktop files', function (): void {
        $appPath = 'app.apk';
        $desktopPath = 'desktop.exe';

        Storage::disk(File::DISK)->put($appPath, 'app-binary');
        Storage::disk(File::DISK)->put($desktopPath, 'desktop-binary');

        File::factory()->create([
            'path' => $appPath,
            'description' => 'App mobile',
            'type' => FileTypeEnum::APP,
        ]);

        File::factory()->create([
            'path' => $desktopPath,
            'description' => 'App desktop',
            'type' => FileTypeEnum::DESKTOP,
        ]);

        livewire(Downloads::class)
            ->assertSee(__('filament.support.downloads_page.available'))
            ->assertSee('app.apk')
            ->assertSee('desktop.exe')
            ->assertSeeHtml('href="'.route('dashboard.files.download', File::query()->where('type', FileTypeEnum::APP)->first()).'"')
            ->assertSeeHtml('href="'.route('dashboard.files.download', File::query()->where('type', FileTypeEnum::DESKTOP)->first()).'"');
    });

    it('uses the latest file when multiple versions exist for the same type', function (): void {
        Storage::disk(File::DISK)->put('old.apk', 'old-binary');
        Storage::disk(File::DISK)->put('new.apk', 'new-binary');

        File::factory()->create([
            'path' => 'old.apk',
            'type' => FileTypeEnum::APP,
        ]);

        File::factory()->create([
            'path' => 'new.apk',
            'type' => FileTypeEnum::APP,
        ]);

        livewire(Downloads::class)
            ->assertSee('new.apk')
            ->assertDontSee('old.apk');
    });
});

describe('Dashboard file download route', function (): void {

    beforeEach(function (): void {
        Storage::fake(File::DISK);
    });

    it('downloads an existing file for authenticated dashboard users', function (): void {
        Storage::disk(File::DISK)->put('report.pdf', 'report-content');

        $file = File::factory()->create([
            'path' => 'report.pdf',
            'type' => FileTypeEnum::APP,
        ]);

        get(route('dashboard.files.download', $file))
            ->assertSuccessful()
            ->assertDownload('report.pdf');
    });

    it('returns not found when the file does not exist on disk', function (): void {
        $file = File::factory()->create([
            'path' => 'missing.pdf',
            'type' => FileTypeEnum::DESKTOP,
        ]);

        get(route('dashboard.files.download', $file))
            ->assertNotFound();
    });
});
