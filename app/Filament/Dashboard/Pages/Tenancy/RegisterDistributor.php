<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Pages\Tenancy;

use App\Models\Distributor;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterDistributor extends RegisterTenant
{
    public static function canView(?Model $tenant = null): bool
    {
        if (Auth::user()?->distributor_id !== null) {
            return false;
        }

        return parent::canView($tenant);
    }

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
        $name = $data['name'] ?? '';
        $data['slug'] = Str::slug(is_string($name) ? $name : '').'-'.Str::lower(Str::random(8));

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRegistration(array $data): Distributor
    {
        $distributor = Distributor::query()->create($data);

        $user = Auth::user();
        if ($user !== null) {
            $user->forceFill(['distributor_id' => $distributor->id])->save();
        }

        return $distributor;
    }
}
