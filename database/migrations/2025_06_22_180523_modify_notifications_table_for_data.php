<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['text', 'subtext', 'is_seen']); // حذف الأعمدة المخصصة
            $table->text('data')->after('id'); // إضافة عمود data
            $table->timestamp('read_at')->nullable()->after('data'); // إضافة read_at (اختياري)
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['data', 'read_at']);
            $table->string('text');
            $table->string('subtext')->nullable();
            $table->boolean('is_seen')->default(false);
        });
    }
};