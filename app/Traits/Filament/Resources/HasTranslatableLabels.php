<?php

declare(strict_types=1);

namespace App\Traits\Filament\Resources;

use Illuminate\Support\Str;

trait HasTranslatableLabels
{
    public static function getNavigationLabel(): string
    {
        $key = static::getResourceTranslationKey();

        return __("resources.{$key}.navigation_label");
    }

    public static function getPluralModelLabel(): string
    {
        $key = static::getResourceTranslationKey();

        return __("resources.{$key}.plural_model_label");
    }

    public static function getModelLabel(): string
    {
        $key = static::getResourceTranslationKey();

        return __("resources.{$key}.model_label");
    }

    protected static function getResourceTranslationKey(): string
    {
        $model = static::getModel();
        $model_name = class_basename($model);

        return Str::snake($model_name);
    }
}
