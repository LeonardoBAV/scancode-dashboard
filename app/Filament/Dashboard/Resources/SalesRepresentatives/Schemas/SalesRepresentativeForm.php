<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\SalesRepresentatives\Schemas;

use App\Models\SalesRepresentative;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SalesRepresentativeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('resources.sales_representative.form.title'))
                    ->icon(Heroicon::OutlinedUserGroup)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        self::cpfInput(),
                        self::nameInput(),
                        self::emailInput(),
                        self::passwordInput(),
                    ]),
            ]);
    }

    protected static function cpfInput(): TextInput
    {
        return TextInput::make('cpf')
            ->label(__('resources.sales_representative.form.cpf'))
            ->required()
            ->unique(SalesRepresentative::class, 'cpf');
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
