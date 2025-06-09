<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intro_answers', function (Blueprint $table) {
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('question_id')->constrained('intro_questions')->onDelete('cascade');
            $table->foreignId('selection_id')->constrained('intro_selections')->onDelete('cascade');

            $table->primary(['user_id', 'question_id', 'selection_id']);

            $table->text('extra_text')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intro_answers');
    }
};