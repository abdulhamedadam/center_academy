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
        Schema::create('sanad_qabds', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('code')->nullable();
            $table->date('date')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanad_qabds');
    }
};
