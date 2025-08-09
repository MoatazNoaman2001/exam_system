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
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['replied_by']);
            $table->dropColumn('replied_by');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->uuid('replied_by')->nullable()->after('replied_at');
            $table->index('replied_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['replied_by']);
            $table->dropColumn('replied_by');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->unsignedBigInteger('replied_by')->nullable()->after('replied_at');
            $table->index('replied_by');
        });
    }
};
