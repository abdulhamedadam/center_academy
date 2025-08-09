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
        Schema::create('tbl_course_materials', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->string('title')->nullable();
            $table->string('file_path')->nullable();
            $table->integer('from_id')->nullable();
            $table->enum('type',['documents','videos','assignments'])->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};
