<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paystubs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('employer', 50);
            $table->uuid('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('pay_period')->default(1);
            $table->unsignedInteger('pay_periods')->default(52);
            $table->decimal('net_pay', 10, 2)->default(0);
            $table->decimal('gross_pay', 10, 2)->default(0);
            $table->decimal('ytd_gross_pay', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->json('deduction_breakdown')->default(new Expression('(JSON_ARRAY())'));
            $table->decimal('ytd_deductions', 10, 2)->default(0);
            $table->enum('pay_frequency', ['weekly', 'bi-weekly', 'semi-monthly', 'monthly'])->default('weekly');
            $table->date('pay_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paystubs');
    }
};
