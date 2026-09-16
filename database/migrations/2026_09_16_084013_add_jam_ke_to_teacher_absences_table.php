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
        Schema::table('teacher_absences', function (Blueprint $table) {
            $table->integer('from_jam_ke')->default(1)->after('class_code');
            $table->integer('to_jam_ke')->default(1)->after('from_jam_ke');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_absences', function (Blueprint $table) {
            $table->dropColumn(['from_jam_ke', 'to_jam_ke']);
        });
    }
};
