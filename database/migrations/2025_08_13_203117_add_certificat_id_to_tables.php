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
        // Add certificate_id to domains table
        Schema::table('domains', function (Blueprint $table) {
            $table->uuid('certificate_id')->nullable()->after('id');
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
        });

        // Add certificate_id to chapters table
        Schema::table('chapters', function (Blueprint $table) {
            $table->uuid('certificate_id')->nullable()->after('id');
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
        });

        // Add certificate_id to exams table
        Schema::table('exams', function (Blueprint $table) {
            $table->uuid('certificate_id')->nullable()->after('id');
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
        });

        // Add certificate_id to plans table
        Schema::table('plans', function (Blueprint $table) {
            $table->uuid('certificate_id')->nullable()->after('id');
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
        });

        // Add certificate_id to user_progress table
        Schema::table('user_progress', function (Blueprint $table) {
            $table->uuid('certificate_id')->nullable()->after('id');
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
        });

        // Add certificate_id to achievements table
        Schema::table('achievements', function (Blueprint $table) {
            $table->uuid('certificate_id')->nullable()->after('id');
            $table->foreign('certificate_id')->references('id')->on('certificates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove certificate_id from all tables
        Schema::table('domains', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
            $table->dropColumn('certificate_id');
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
            $table->dropColumn('certificate_id');
        });

        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
            $table->dropColumn('certificate_id');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
            $table->dropColumn('certificate_id');
        });

        Schema::table('user_progress', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
            $table->dropColumn('certificate_id');
        });

        Schema::table('achievements', function (Blueprint $table) {
            $table->dropForeign(['certificate_id']);
            $table->dropColumn('certificate_id');
        });
    }
};
