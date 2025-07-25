<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     */
    public function up(): void
    {
        Schema::dropIfExists('question_exam_answer');
        
        Schema::create('question_exam_answer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('answer');
            $table->text('answer-ar');
            $table->text('reason')->nullable()->comment('Explanation for why this answer is correct/incorrect (English)');
            $table->text('reason-ar')->nullable()->comment('Explanation for why this answer is correct/incorrect (Arabic)');
            $table->boolean('is_correct')->default(false);
            $table->uuid('exam_question_id');
            $table->timestamps();
            
            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_exam_answer');
        
        Schema::create('question_exam_answer', function (Blueprint $table) {
            $table->id();
            $table->text('answer');
            $table->text('answer-ar');
            $table->text('reason')->nullable();
            $table->text('reason-ar')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->uuid('exam_question_id');
            $table->timestamps();
            
            $table->foreign('exam_question_id')->references('id')->on('exam_questions')->onDelete('cascade');
        });
    }
};