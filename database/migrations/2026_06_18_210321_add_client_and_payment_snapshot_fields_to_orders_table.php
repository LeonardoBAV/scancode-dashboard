<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('client_cpf_cnpj')->after('buyer_phone');
            $table->string('client_corporate_name')->after('client_cpf_cnpj');
            $table->string('client_fantasy_name')->nullable()->after('client_corporate_name');
            $table->string('payment_method_name')->nullable()->after('client_fantasy_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn([
                'client_cpf_cnpj',
                'client_corporate_name',
                'client_fantasy_name',
                'payment_method_name',
            ]);
        });
    }
};
