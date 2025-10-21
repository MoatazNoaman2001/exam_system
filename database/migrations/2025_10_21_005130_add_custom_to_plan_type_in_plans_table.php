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
        DB::statement("ALTER TYPE plan_type ADD VALUE IF NOT EXISTS 'custom'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("-- WARNING: Cannot remove enum value 'custom' from plan_type. Manual intervention required.");
    }
};
