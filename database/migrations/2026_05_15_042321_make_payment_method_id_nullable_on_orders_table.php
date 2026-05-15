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
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropForeign(['payment_method_id']);
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->unsignedBigInteger('payment_method_id')->nullable()->change();
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropForeign(['payment_method_id']);
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->unsignedBigInteger('payment_method_id')->nullable(false)->change();
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->restrictOnDelete();
        });
    }
};
