<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('text');
            $table->text('content');
            $table->boolean('is_completed')->default(false);
            $table->integer('count')->unsigned()->min(0);
            $table->foreignUuid('chapter_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('domain_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slides');
    }
};