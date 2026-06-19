<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Schema;

class EditDistributorProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return __('filament.tenancy.edit_distributor_profile');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::nameInput(),
            ]);
    }

    protected static function nameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('filament.tenancy.distributor_name'))
            ->required()
            ->maxLength(255);
    }
}
