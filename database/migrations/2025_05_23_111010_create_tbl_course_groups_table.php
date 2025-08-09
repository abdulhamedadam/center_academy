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
        Schema::create('tbl_course_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->bigInteger('code')->nullable()->unique();
            $table->integer('course_id')->nullable();
            $table->integer('instructor_id')->nullable();
            $table->integer('hall_id')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->time('end_time')->nullable();
            $table->time('start_time')->nullable();
            $table->json('days')->nullable();
            $table->integer('max_number')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_course_groups');
    }
};
