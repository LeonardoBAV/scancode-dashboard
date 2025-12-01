<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clients\Schemas;

use App\Models\Client;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('resources.client.form.title'))
                    ->icon(Heroicon::OutlinedUsers)
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        self::cpfCnpjInput(),
                        self::corporateNameInput(),
                        self::fantasyNameInput(),
                        self::emailInput(),
                        self::phoneInput(),
                    ]),
            ]);
    }

    protected static function cpfCnpjInput(): TextInput
    {
        return TextInput::make('cpf_cnpj')
            ->label(__('resources.client.form.cpf_cnpj'))
            ->required()
            ->unique(Client::class, 'cpf_cnpj');
    }

    protected static function corporateNameInput(): TextInput
    {
        return TextInput::make('corporate_name')
            ->label(__('resources.client.form.corporate_name'));
    }

    protected static function fantasyNameInput(): TextInput
    {
        return TextInput::make('fantasy_name')
            ->label(__('resources.client.form.fantasy_name'));
    }

    protected static function emailInput(): TextInput
    {
        return TextInput::make('email')
            ->label(__('resources.client.form.email'))
            ->email();
    }

    protected static function phoneInput(): TextInput
    {
        return TextInput::make('phone')
            ->label(__('resources.client.form.phone'))
            ->tel();
    }
}
