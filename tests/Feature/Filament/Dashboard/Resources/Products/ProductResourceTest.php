<?php

declare(strict_types=1);

use App\Filament\Dashboard\Resources\Products\Pages\CreateProduct;
use App\Filament\Dashboard\Resources\Products\Pages\EditProduct;
use App\Filament\Dashboard\Resources\Products\Pages\ListProducts;
use App\Filament\Dashboard\Resources\Products\Pages\ViewProduct;
use App\Filament\Dashboard\Resources\Products\ProductResource;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;

describe('Resource - Product:', function (): void {

    beforeEach(function (): void {
        Product::factory()->create();
    });

    test('resource has correct model', function (): void {
        expect(ProductResource::getModel())->toBe(Product::class);
    });

    test('resource has record title attribute', function (): void {
        $titleAttribute = ProductResource::getRecordTitleAttribute();

        expect($titleAttribute)->toBe('sku');
    });

    test('resource has correct pages configured', function (): void {
        $pages = ProductResource::getPages();

        expect($pages)->toHaveKey('index')
            ->and($pages)->toHaveKey('create')
            ->and($pages)->toHaveKey('view')
            ->and($pages)->toHaveKey('edit')
            ->and($pages['index']->getPage())->toBe(ListProducts::class)
            ->and($pages['create']->getPage())->toBe(CreateProduct::class)
            ->and($pages['view']->getPage())->toBe(ViewProduct::class)
            ->and($pages['edit']->getPage())->toBe(EditProduct::class);
    });

    test('index page loads correctly', function (): void {
        $url = ProductResource::getUrl('index');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ListProducts::class);
    });

    test('create page loads correctly', function (): void {
        $url = ProductResource::getUrl('create');

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(CreateProduct::class);
    });

    test('view page loads correctly', function (): void {
        $product = Product::firstOrFail();

        $url = ProductResource::getUrl('view', ['record' => $product]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(ViewProduct::class);
    });

    test('edit page loads correctly', function (): void {
        $product = Product::firstOrFail();

        $url = ProductResource::getUrl('edit', ['record' => $product]);

        actingAs(User::factory()->create())
            ->get($url)
            ->assertStatus(200)
            ->assertSeeLivewire(EditProduct::class);
    });

});
