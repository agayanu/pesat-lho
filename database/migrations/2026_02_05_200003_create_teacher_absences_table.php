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
        Schema::create('teacher_absences', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('teacher_name');
            $table->string('class_code');
            $table->enum('status', ['Izin', 'Sakit', 'Dinas', 'Alpha']);
            $table->string('substitute_teacher')->nullable();
            $table->text('task_description')->nullable();
            $table->string('piket_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_absences');
    }
};
