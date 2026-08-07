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
        Schema::create('special_activity_reports', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('unit_name');
            $table->string('class_or_participants');
            $table->text('material_activity');
            $table->string('pic_teacher');
            $table->text('notes')->nullable();
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_activity_reports');
    }
};
