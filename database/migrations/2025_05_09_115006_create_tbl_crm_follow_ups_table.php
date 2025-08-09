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
        Schema::create('tbl_crm_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->date('follow_up_date')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->text('note')->nullable();
            $table->integer('result')->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('communication_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_crm_follow_ups');
    }
};
