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
        Schema::create('tbl_instructors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('code')->nullable()->unique();
            $table->string('name')->nullable();
            $table->text('bio')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->integer('experience')->nullable();
            $table->string('qualifications')->nullable();
            $table->string('user_id')->nullable();
            $table->string('image')->nullable();
            $table->decimal('salary',10,2)->nullable();
            $table->decimal('course_percentage')->nullable();
            $table->integer('gender')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('age')->nullable();
            $table->string('specialization')->nullable();
            $table->integer('status')->default(1);
            $table->string('hire_date')->nullable();
            $table->text('administrative_notes')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
