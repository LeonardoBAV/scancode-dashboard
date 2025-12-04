<?php

namespace App\Filament\Dashboard\Resources\PaymentMethods;

use App\Filament\Dashboard\Resources\PaymentMethods\Pages\CreatePaymentMethod;
use App\Filament\Dashboard\Resources\PaymentMethods\Pages\EditPaymentMethod;
use App\Filament\Dashboard\Resources\PaymentMethods\Pages\ListPaymentMethods;
use App\Filament\Dashboard\Resources\PaymentMethods\Pages\ViewPaymentMethod;
use App\Filament\Dashboard\Resources\PaymentMethods\Schemas\PaymentMethodForm;
use App\Filament\Dashboard\Resources\PaymentMethods\Schemas\PaymentMethodInfolist;
use App\Filament\Dashboard\Resources\PaymentMethods\Tables\PaymentMethodsTable;
use App\Models\PaymentMethod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PaymentMethodForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PaymentMethodInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaymentMethodsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentMethods::route('/'),
            'create' => CreatePaymentMethod::route('/create'),
            'view' => ViewPaymentMethod::route('/{record}'),
            'edit' => EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
