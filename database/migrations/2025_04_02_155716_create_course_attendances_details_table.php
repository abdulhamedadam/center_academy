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
        Schema::table('tbl_course_attendances', function (Blueprint $table) {
            $table->dropColumn('student_id');
            $table->dropColumn('status');
            $table->integer('created_by')->nullable()->change();
            $table->integer('updated_by')->nullable()->change();
        });
        Schema::create('tbl_course_attendances_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('tbl_course_attendances')->onDelete('cascade');
            $table->integer('student_id');
            $table->enum('status', [1, 2, 3]);
            $table->timestamps();
            $table->index(['attendance_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_attendances_details');
    }
};
