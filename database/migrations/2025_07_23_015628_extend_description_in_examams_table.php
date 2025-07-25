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
        Schema::table('exams', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->text('description-ar')->nullable()->change();
            
            $table->string('text', 500)->change();
            $table->string('text-ar', 500)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->string('description', 255)->nullable()->change();
            $table->string('description-ar', 255)->nullable()->change();
            $table->string('text', 255)->change();
            $table->string('text-ar', 255)->change();
        });
    }
};
