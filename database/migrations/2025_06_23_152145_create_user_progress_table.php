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
        DB::statement("CREATE TYPE progress_status AS ENUM ('not_started', 'in_progress', 'completed')");

        Schema::create('user_progress', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->ulid('plan_id');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->uuid('slide_id')->nullable();
            $table->foreign('slide_id')->references('id')->on('slides')->onDelete('set null');
            $table->uuid('exam_id')->nullable();
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('set null');
            $table->string('status')->default('not_started');
            $table->dateTime('completed_at')->nullable();
            $table->float('score')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->check('status::progress_status IS NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
        DB::statement("DROP TYPE IF EXISTS progress_status");
    }
};
