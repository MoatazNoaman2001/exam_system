<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('text');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_manually_added')->default(false);
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('due_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};