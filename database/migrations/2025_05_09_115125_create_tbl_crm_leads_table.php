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
        Schema::create('tbl_crm_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('source')->nullable();
            $table->integer('assigned_to')->nullable();
            $table->text('note')->nullable();
            $table->integer('source')->nullable()->change();
            $table->string('first_contact_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_crm_leads');
    }
};
