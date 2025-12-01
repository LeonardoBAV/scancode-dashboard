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
                TextInput::make('cpf_cnpj')
                    ->required()
                    ->unique(Client::class, 'cpf_cnpj'),
                TextInput::make('corporate_name'),
                TextInput::make('fantasy_name'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
            ]);
    }
}
