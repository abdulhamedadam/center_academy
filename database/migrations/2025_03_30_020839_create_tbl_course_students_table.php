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
        Schema::create('tbl_course_students', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->enum('type',['online','offline','hybrid'])->default('offline')->nullable();
            $table->tinyInteger('is_active')->default(1)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_course_students');
    }
};
