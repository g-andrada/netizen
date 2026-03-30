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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('institution', 45);
            $table->string('account_number', 1024);
            $table->enum('account_type', ['chequing', 'savings', 'credit_card', 'investment']);
            $table->string('account_holder');
            $table->uuid('account_holder_id');
            $table->decimal('balance', 10, 2)->default(0);
            $table->enum('ownership_type', ['individual', 'joint']);
            $table->uuid('joint_id')->nullable();
            $table->enum('status', ['active', 'inactive', 'closed', 'pending', 'frozen']);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_holder_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
