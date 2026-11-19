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
            if (!Schema::hasColumn('teacher_absences', 'external_substitute_teacher')) {
                $table->string('external_substitute_teacher')->nullable()->after('substitute_teacher');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_absences', function (Blueprint $table) {
            if (Schema::hasColumn('teacher_absences', 'external_substitute_teacher')) {
                $table->dropColumn('external_substitute_teacher');
            }
        });
    }
};
