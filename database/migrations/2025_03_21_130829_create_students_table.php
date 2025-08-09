<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_students', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('code')->nullable()->unique();
            $table->string('full_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('age')->nullable();
            $table->string('address')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('registration_date')->nullable();
            $table->integer('status')->nullable();
            $table->text('notes')->nullable();
            $table->string('image')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('region_id')->nullable();
            $table->string('educational_qualification')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('educational_institution')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
