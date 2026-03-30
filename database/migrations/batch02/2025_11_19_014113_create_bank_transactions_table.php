<?php

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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_id');
            $table->foreign('account_id')->references('id')->on('bank_accounts')->onDelete('cascade');
            $table->uuid('merchant_id')->nullable();
            $table->uuid('payee_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->uuid('transaction_type_id');
            $table->foreign('transaction_type_id')->references('id')->on('bank_transaction_types')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
