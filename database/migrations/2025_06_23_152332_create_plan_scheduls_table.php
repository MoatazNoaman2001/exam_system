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
        DB::statement("CREATE TYPE schedule_status AS ENUM ('pending', 'completed', 'late')");

        Schema::create('plan_schedules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->uuid('slide_id')->nullable();
            $table->foreign('slide_id')->references('id')->on('slides')->onDelete('set null');
            $table->uuid('exam_id')->nullable();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('set null');
            $table->date('scheduled_date');
            $table->string('status')->default('pending');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->check('status::schedule_status IS NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_schedules');
        DB::statement("DROP TYPE IF EXISTS schedule_status");
    }
};
