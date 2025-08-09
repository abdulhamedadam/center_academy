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
        Schema::create('tbl_course_installments', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('course_payment_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->string('due_date')->nullable();

            $table->decimal('remaining_amount',10,2)->nullable();
            $table->enum('status',['paid','remaining','late'])->default('late')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_course_installments');
    }
};
