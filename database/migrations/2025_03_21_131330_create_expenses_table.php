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
        Schema::create('tbl_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('band_id')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->string('expense_date')->nullable();
            $table->text('description')->nullable();
            $table->integer('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
