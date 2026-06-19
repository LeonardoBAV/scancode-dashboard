<?php

declare(strict_types=1);

namespace App\Enums;

enum FileTypeEnum: string
{
    case APP = 'app';
    case DESKTOP = 'desktop';

    public function label(): string
    {
        return match ($this) {
            self::APP => __('enums.file_type.app'),
            self::DESKTOP => __('enums.file_type.desktop'),
        };
    }
}
