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
        Schema::table('tbl_levels', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->integer('created_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_levels', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->integer('created_by')->nullable(false)->change();
        });
    }
};
