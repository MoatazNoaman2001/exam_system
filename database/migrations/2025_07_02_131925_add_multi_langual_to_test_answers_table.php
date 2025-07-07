<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('test_answers', function (Blueprint $table) {
            $table->string("text_en")->nullable()->after('text');
            $table->string('text_ar')->nullable()->after('text_en');

            $table->index(['test_id', 'created_at']);
        });

        DB::statement("UPDATE test_answers SET text_en = text WHERE text_en IS NULL");
        Schema::table('test_answers', function (Blueprint $table) {
            $table->text('text_en')->nullable(false)->change();
            $table->dropColumn('text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_answers', function (Blueprint $table) {
            $table->string('text')->nullable()->after('id');
            $table->dropIndex(['test_id', 'created_at']);
        });

        DB::statement("UPDATE test_answers SET text = text_en WHERE text IS NULL");

        Schema::table('test_answers', function (Blueprint $table) {
            $table->dropColumn(['text_ar', 'text_en']);
        });
    }
};
