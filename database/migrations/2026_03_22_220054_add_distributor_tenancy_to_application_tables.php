<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('distributor_id')->nullable()->constrained('distributors')->restrictOnDelete();
        });

        foreach ([
            'product_categories',
            'products',
            'clients',
            'payment_methods',
            'sales_representatives',
            'orders',
            'order_items',
        ] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->foreignId('distributor_id')->constrained('distributors')->restrictOnDelete();
            });
        }

        Schema::table('product_categories', function (Blueprint $table): void {
            $table->dropUnique(['name']);
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropUnique(['sku']);
            $table->dropUnique(['barcode']);
        });

        Schema::table('clients', function (Blueprint $table): void {
            $table->dropUnique(['cpf_cnpj']);
        });

        Schema::table('payment_methods', function (Blueprint $table): void {
            $table->dropUnique(['name']);
        });

        Schema::table('sales_representatives', function (Blueprint $table): void {
            $table->dropUnique(['cpf']);
        });

        Schema::table('product_categories', function (Blueprint $table): void {
            $table->unique(['distributor_id', 'name']);
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->unique(['distributor_id', 'sku']);
            $table->unique(['distributor_id', 'barcode']);
        });

        Schema::table('clients', function (Blueprint $table): void {
            $table->unique(['distributor_id', 'cpf_cnpj']);
        });

        Schema::table('payment_methods', function (Blueprint $table): void {
            $table->unique(['distributor_id', 'name']);
        });

        Schema::table('sales_representatives', function (Blueprint $table): void {
            $table->unique(['distributor_id', 'cpf']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table): void {
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
        });

        Schema::table('sales_representatives', function (Blueprint $table): void {
            $table->dropUnique(['distributor_id', 'cpf']);
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
            $table->unique('cpf');
        });

        Schema::table('payment_methods', function (Blueprint $table): void {
            $table->dropUnique(['distributor_id', 'name']);
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
            $table->unique('name');
        });

        Schema::table('clients', function (Blueprint $table): void {
            $table->dropUnique(['distributor_id', 'cpf_cnpj']);
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
            $table->unique('cpf_cnpj');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropUnique(['distributor_id', 'sku']);
            $table->dropUnique(['distributor_id', 'barcode']);
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
            $table->unique('sku');
            $table->unique('barcode');
        });

        Schema::table('product_categories', function (Blueprint $table): void {
            $table->dropUnique(['distributor_id', 'name']);
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
            $table->unique('name');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['distributor_id']);
            $table->dropColumn('distributor_id');
        });
    }
};
