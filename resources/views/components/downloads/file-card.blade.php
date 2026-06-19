@props([
    'file' => null,
    'title' => null,
    'hint',
    'icon',
])

@php
    $available = $file?->isDownloadable() ?? false;
    $displayTitle = $title ?? $file?->displayName() ?? $hint;
@endphp

<div @class([
    'relative flex h-full flex-col overflow-hidden rounded-xl border p-6 shadow-sm transition',
    'border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900' => $available,
    'border-gray-200 bg-gray-50 dark:border-white/5 dark:bg-gray-950/40' => ! $available,
])>
    <div class="flex items-start gap-4">
        <div @class([
            'flex size-12 shrink-0 items-center justify-center rounded-xl',
            'bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400' => $available,
            'bg-gray-100 text-gray-400 dark:bg-white/5 dark:text-gray-500' => ! $available,
        ])>
            <x-filament::icon
                :icon="$icon"
                class="size-6"
            />
        </div>

        <div class="min-w-0 flex-1 space-y-2">
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="text-base font-semibold text-gray-950 dark:text-white">
                    {{ $displayTitle }}
                </h3>

                @if ($available)
                    <x-filament::badge color="success">
                        {{ __('filament.support.downloads_page.available') }}
                    </x-filament::badge>
                @else
                    <x-filament::badge color="gray">
                        {{ __('filament.support.downloads_page.unavailable') }}
                    </x-filament::badge>
                @endif
            </div>

            <p @class([
                'text-sm leading-relaxed',
                'text-gray-600 dark:text-gray-300' => $available,
                'text-gray-500 dark:text-gray-400' => ! $available,
            ])>
                {{ $hint }}
            </p>

            @if ($available)
                <dl class="grid gap-1 text-sm text-gray-500 dark:text-gray-400">
                    @if ($file->fileName())
                        <div class="flex flex-wrap gap-x-2">
                            <dt class="font-medium text-gray-700 dark:text-gray-300">
                                {{ $file->fileName() }}
                            </dt>
                            @if ($file->formattedSize())
                                <dd>{{ $file->formattedSize() }}</dd>
                            @endif
                        </div>
                    @endif

                    @if (filled($file->description) && $title !== null)
                        <dd>{{ $file->description }}</dd>
                    @endif
                </dl>
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('filament.support.downloads_page.unavailable_hint') }}
                </p>
            @endif
        </div>
    </div>

    <div class="mt-6">
        @if ($available)
            <a
                href="{{ $file->getDashboardDownloadUrl() }}"
                class="fi-btn fi-size-md fi-ac-btn-color-primary inline-grid grid-flow-col items-center justify-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 focus-visible:ring-2 w-full sm:w-auto"
            >
                <x-filament::icon
                    icon="phosphor-download-simple"
                    class="fi-btn-icon size-5"
                />
                <span>{{ __('filament.support.downloads_page.download') }}</span>
            </a>
        @else
            <x-filament::button
                color="gray"
                disabled
                icon="phosphor-prohibit"
                class="w-full sm:w-auto"
            >
                {{ __('filament.support.downloads_page.unavailable') }}
            </x-filament::button>
        @endif
    </div>
</div>
