<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color as SupportColor;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class RgbUiAvatarsProvider implements AvatarProvider
{
    const DEFAULT_COLOR = '000000';

    public function get(Model|Authenticatable $record): string
    {
        $name = str(Filament::getNameForDefaultAvatar($record))
            ->trim()
            ->explode(' ')
            ->map(fn (string $segment): string => filled($segment) ? mb_substr($segment, 0, 1) : '')
            ->join(' ');

        $bg = $this->getBackgroundHex();

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=FFFFFF&background='.$bg;
    }

    protected function getBackgroundHex(): string
    {
        $color = (array) FilamentColor::getColor('primary');

        $oklch = $color[950];

        $rgb = SupportColor::convertToRgb((string) $oklch);

        sscanf($rgb, 'rgb(%d, %d, %d)', $r, $g, $b);

        return sprintf('%02X%02X%02X', $r, $g, $b);
    }
}
