<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // التأكد من أن id يولد UUID افتراضيًا
            $table->uuid('id')->primary()->change()->default(\Illuminate\Support\Str::uuid())->nullable(false);
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary()->change()->default(null); // التراجع عن التغيير
        });
    }
};