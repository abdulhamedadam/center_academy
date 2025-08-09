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
        Schema::create('tbl_course_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->decimal('total_amount',10,2)->nullable();
            $table->decimal('paid_amount',10,2)->nullable();
            $table->enum('payment_type',['cash','installment','payment_installment'])->nullable();
            $table->enum('status',['paid','remaining','late'])->default('late')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_course_payments');
    }
};
