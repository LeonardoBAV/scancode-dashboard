<x-filament-panels::page>
    <div class="flex flex-col gap-8">
        @if ($this->getOtherFiles()->isNotEmpty())
            <section class="flex flex-col gap-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                        {{ __('filament.support.downloads_page.other_downloads') }}
                    </h2>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($this->getOtherFiles() as $file)
                        <x-downloads.file-card
                            :file="$file"
                            :hint="$file->displayName()"
                            icon="phosphor-file-arrow-down"
                        />
                    @endforeach
                </div>
            </section>
        @endif

        <section class="flex flex-col gap-4">
            <div>
                <h2 class="text-base font-semibold text-gray-950 dark:text-white">
                    {{ __('filament.support.downloads_page.applications') }}
                </h2>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <x-downloads.file-card
                    :file="$this->getAppFile()"
                    :hint="__('filament.support.downloads_page.app_hint')"
                    :title="\App\Enums\FileTypeEnum::APP->label()"
                    icon="phosphor-device-mobile"
                />

                <x-downloads.file-card
                    :file="$this->getDesktopFile()"
                    :hint="__('filament.support.downloads_page.desktop_hint')"
                    :title="\App\Enums\FileTypeEnum::DESKTOP->label()"
                    icon="phosphor-desktop"
                />
            </div>
        </section>
    </div>
</x-filament-panels::page>
