<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Clients\Schemas;

use App\Models\Client;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::cpfCnpjInput(),
                self::corporateNameInput(),
                self::fantasyNameInput(),
                self::emailInput(),
                self::phoneInput(),
                self::carrierInput(),
            ]);
    }

    protected static function cpfCnpjInput(): TextInput
    {
        return TextInput::make('cpf_cnpj')
            ->label(__('resources.client.form.cpf_cnpj'))
            ->required()
            ->scopedUnique(Client::class, 'cpf_cnpj', ignoreRecord: true);
    }

    protected static function corporateNameInput(): TextInput
    {
        return TextInput::make('corporate_name')
            ->label(__('resources.client.form.corporate_name'))
            ->required();
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

    protected static function carrierInput(): TextInput
    {
        return TextInput::make('carrier')
            ->label(__('resources.client.form.carrier'));
    }
}
