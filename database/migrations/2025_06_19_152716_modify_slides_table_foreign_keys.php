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
    public function up()
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);
            $table->dropForeign(['domain_id']);

            $table->uuid('chapter_id')->nullable()->change();
            $table->uuid('domain_id')->nullable()->change();

            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
        });

        DB::statement('ALTER TABLE slides DROP CONSTRAINT IF EXISTS check_chapter_or_domain');
        DB::statement('ALTER TABLE slides ADD CONSTRAINT check_chapter_or_domain CHECK (chapter_id IS NOT NULL OR domain_id IS NOT NULL)');

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('slides', function (Blueprint $table) {
            DB::statement('ALTER TABLE slides DROP CONSTRAINT IF EXISTS check_chapter_or_domain');
            $table->dropForeign(['chapter_id']);
            $table->dropForeign(['domain_id']);

            $table->uuid('chapter_id')->nullable(false)->change();
            $table->uuid('domain_id')->nullable(false)->change();

            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
        });
    }
};