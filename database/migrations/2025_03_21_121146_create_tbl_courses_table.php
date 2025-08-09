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
        Schema::create('tbl_courses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('code')->nullable()->unique();
            $table->integer('category_id')->nullable();
            $table->integer('level_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('instructor_id')->nullable();
            $table->decimal('price',10,2)->nullable();
            $table->enum('discount_type',['p','v'])->nullable();
            $table->decimal('discount',10,2)->nullable();
            $table->decimal('total_price',10,2)->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('max_students')->nullable();
            $table->enum('status',[1,2,3])->nullable();
            $table->decimal('instructor_percentage',10,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_courses');
    }
};
