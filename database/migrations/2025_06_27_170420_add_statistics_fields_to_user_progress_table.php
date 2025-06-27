<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->integer('lessons_completed')->default(0);
            $table->integer('exams_completed')->default(0);
            $table->integer('questions_completed')->default(0);
            $table->integer('domains_completed')->default(0);
            $table->integer('lessons_total')->default(0);
            $table->integer('exams_total')->default(0);
            $table->integer('domains_total')->default(0);
            $table->integer('points')->default(0);
            $table->integer('streak_days')->default(0);
            $table->float('days_left')->default(0);
            $table->float('progress')->default(0);
            $table->string('current_level')->nullable();
            $table->integer('points_to_next_level')->default(0);
            $table->date('plan_end_date')->nullable();
            $table->integer('plan_duration')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropColumn([
                'lessons_completed',
                'exams_completed',
                'questions_completed',
                'domains_completed',
                'lessons_total',
                'exams_total',
                'domains_total',
                'points',
                'streak_days',
                'days_left',
                'progress',
                'current_level',
                'points_to_next_level',
                'plan_end_date',
                'plan_duration',
            ]);
        });
    }
};
