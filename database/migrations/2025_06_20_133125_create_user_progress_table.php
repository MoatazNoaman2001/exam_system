<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('points')->default(0);
            $table->string('current_level')->default('مبتدئ');
            $table->integer('points_to_next_level')->default(100);
            $table->integer('days_left')->default(0);
            $table->integer('plan_duration')->default(90);
            
            $table->date('plan_end_date')->nullable();
            $table->integer('progress')->default(0);
            $table->integer('domains_completed')->default(0);
            $table->integer('domains_total')->default(0);
            $table->integer('lessons_completed')->default(0);
            $table->integer('lessons_total')->default(0);
            $table->integer('exams_completed')->default(0);
            $table->integer('exams_total')->default(0);
            $table->integer('questions_completed')->default(0);
            $table->integer('questions_total')->default(0);
            $table->integer('lessons_milestone')->default(0);
            $table->integer('questions_milestone')->default(0);
            $table->integer('streak_days')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};