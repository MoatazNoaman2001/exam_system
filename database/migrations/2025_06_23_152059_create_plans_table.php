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
        DB::statement("CREATE TYPE plan_type AS ENUM ('1_month', '2_months', '3_months', 'custom')");

        Schema::create('plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('plan_type')->default('1_month');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('custom_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->check('plan_type::plan_type IS NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
        DB::statement("DROP TYPE IF EXISTS plan_type");
    }
};
