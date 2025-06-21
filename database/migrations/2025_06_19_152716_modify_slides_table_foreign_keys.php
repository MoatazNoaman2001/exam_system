<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropForeign(['chapter_id']);
            $table->dropForeign(['domain_id']);

            $table->uuid('chapter_id')->nullable(false)->change();
            $table->uuid('domain_id')->nullable(false)->change();

            $table->foreign('chapter_id')->references('id')->on('chapters')->onDelete('cascade');
            $table->foreign('domain_id')->references('id')->on('domains')->onDelete('cascade');
        });
    }
};