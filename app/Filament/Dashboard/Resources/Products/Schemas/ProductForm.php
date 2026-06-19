<?php

declare(strict_types=1);

namespace App\Filament\Dashboard\Resources\Products\Schemas;

use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                self::nameInput(),
                self::skuInput(),
                self::productCategoryInput(),
                self::priceInput(),
                self::barcodeInput(),
            ]);
    }

    protected static function skuInput(): TextInput
    {
        return TextInput::make('sku')
            ->label(__('resources.product.form.sku'))
            ->required()
            ->scopedUnique(Product::class, 'sku', ignoreRecord: true);
    }

    protected static function barcodeInput(): TextInput
    {
        return TextInput::make('barcode')
            ->label(__('resources.product.form.barcode'))
            ->nullable()
            ->scopedUnique(Product::class, 'barcode', ignoreRecord: true);
    }

    protected static function nameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('resources.product.form.name'))
            ->required();
    }

    protected static function priceInput(): TextInput
    {
        return TextInput::make('price')
            ->label(__('resources.product.form.price'))
            ->required()
            ->numeric()
            ->prefix('$');
    }

    protected static function productCategoryInput(): Select
    {
        return Select::make('product_category_id')
            ->label(__('resources.product.form.product_category_name'))
            ->searchable()
            ->preload()
            ->relationship('productCategory', 'name')
            ->createOptionAction(fn (Action $action): Action => $action->successNotificationTitle(__('filament-actions::create.single.notifications.created.title')))
            ->editOptionAction(fn (Action $action): Action => $action->successNotificationTitle(__('filament-actions::edit.single.notifications.saved.title')))
            ->createOptionForm([
                self::productCategoryNameInput(),
                self::productCategoryDistributorInput(),
            ])
            ->editOptionForm([
                self::productCategoryNameInput(),
            ]);
    }

    protected static function productCategoryNameInput(): TextInput
    {
        return TextInput::make('name')
            ->label(__('resources.product.form.product_category_name'))
            ->required()
            ->scopedUnique(ProductCategory::class, 'name');
    }

    protected static function productCategoryDistributorInput(): Hidden
    {
        // hidden input para distribuidora
        return Hidden::make('distributor_id')
            ->default(fn () => Auth::user()->distributor_id);
    }
}
