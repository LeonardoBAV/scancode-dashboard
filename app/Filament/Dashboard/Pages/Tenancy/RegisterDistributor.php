<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages\Tenancy;

use App\Models\Distributor;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RegisterDistributor extends RegisterTenant
{
    public static function getLabel(): string
    {
        return __('filament.tenancy.register_distributor');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('filament.tenancy.distributor_name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(8));

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRegistration(array $data): Distributor
    {
        $distributor = Distributor::query()->create($data);

        $user = auth()->user();
        if ($user !== null) {
            $user->forceFill(['distributor_id' => $distributor->id])->save();
        }

        return $distributor;
    }
}
