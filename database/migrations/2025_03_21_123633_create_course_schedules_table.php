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
        Schema::create('tbl_course_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->string('day')->nullable();
            $table->string('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status',['scheduled','completed','cancelled','postponed'])->default('scheduled')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_schedules');
    }
};
