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
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('total_time_spent')->default(0);
            $table->integer('current_question_index')->default(0);
            $table->json('answered_questions')->nullable();
            $table->json('question_order')->nullable();
            $table->dropColumn('score');
            // $table->decimal('score', 5, 2)->nullable();
            // $table->enum('status', ['in_progress', 'completed', 'paused', 'expired'])->default('in_progress');

            $table->index(['user_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropColumn('started_at');
            $table->dropColumn('last_activity_at');
            $table->dropColumn('completed_at');
            $table->dropColumn('total_time_spent');
            $table->dropColumn('current_question_index');
            $table->dropColumn('answered_questions');
            $table->dropColumn('question_order');

            $table->dropIndex(['user_id', 'exam_id']);
        });
    }
};
