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
        Schema::table('tests', function (Blueprint $table) {
            $table->string('question_en')->nullable()->after('question');
            $table->string('question_ar')->nullable()->after('question_en');

            $table->index(['slide_id', 'created_at']);
        });

         DB::statement("UPDATE tests SET question_en = question WHERE question_en IS NULL");
        
         Schema::table('tests', function (Blueprint $table) {
             $table->text('question_en')->nullable(false)->change();
             $table->dropColumn('question');
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->string('question')->nullable()->after('id');
            $table->dropIndex(['slide_id', 'created_at']);
        });

        DB::statement("UPDATE tests SET question = question_en WHERE question IS NULL");

        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn(['question_ar', 'question_en']);
        });

    }
};
