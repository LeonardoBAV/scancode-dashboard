<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Schemas;

use App\Models\SalesRepresentative;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SalesRepresentativeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::cpfInput(),
                self::nameInput(),
                self::emailInput(),
                self::passwordInput(),
            ]);
    }

    protected static function cpfInput(): TextInput
    {
        return TextInput::make('cpf')
            ->label(__('resources.sales_representative.form.cpf'))
            ->required()
            ->scopedUnique(SalesRepresentative::class, 'cpf', ignoreRecord: true);
    }

    protected static function nameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('resources.sales_representative.form.name'))
            ->required();
    }

    protected static function emailInput(): TextInput
    {
        return TextInput::make('email')
            ->label(__('resources.sales_representative.form.email'))
            ->email()
            ->required();
    }

    protected static function passwordInput(): TextInput
    {
        return TextInput::make('password')
            ->label(__('resources.sales_representative.form.password'))
            ->password()
            ->required();
    }
}
