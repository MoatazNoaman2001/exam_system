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
        Schema::table('question_exam_answer', function (Blueprint $table) {
            $table->text('reason')->nullable()->after('answer-ar')->comment('Explanation for why this answer is correct/incorrect (English)');
            $table->text('reason-ar')->nullable()->after('reason')->comment('Explanation for why this answer is correct/incorrect (Arabic)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_exam_answer', function (Blueprint $table) {
            $table->dropColumn(['reason', 'reason-ar']);

        });
    }
};
