<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intro_selections', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->boolean('has_extra_text')->default(false);
            $table->foreignUuid('question_id')->constrained('intro_questions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intro_selections');
    }
};