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
        Schema::create('tbl_course_assignments_results', function (Blueprint $table) {
            $table->id();
            $table->integer('assignment_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->text('answer_text')->nullable();
            $table->decimal('grade',6,2)->nullable();
            $table->text('feedback')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_course_assignments_results');
    }
};
