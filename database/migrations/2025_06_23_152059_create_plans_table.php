<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("CREATE TYPE plan_type AS ENUM ('6_weeks', '10_weeks')");

        Schema::create('plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUuid('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('plan_type')->default('1_month');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('custom_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add the check constraint using raw SQL
        DB::statement("ALTER TABLE plans ADD CONSTRAINT check_plan_type_valid CHECK (plan_type::plan_type IS NOT NULL)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the constraint first
        DB::statement("ALTER TABLE plans DROP CONSTRAINT IF EXISTS check_plan_type_valid");
        Schema::dropIfExists('plans');
        DB::statement("DROP TYPE IF EXISTS plan_type");
    }
};