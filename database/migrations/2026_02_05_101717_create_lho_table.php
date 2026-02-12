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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('homeroom');
            $table->enum('school', ['Unggulan', 'Reguler']);
            $table->string('user');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('id_siswa');
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('classes');
            $table->enum('program', ['Pioneer', 'Unggulan', 'Reguler']);
            $table->string('studentday')->nullable();
            $table->string('user');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('user');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('student_absences', function (Blueprint $table) {
            $table->id();
            $table->integer('student');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha']);
            $table->string('user');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('teaching_activities', function (Blueprint $table) {
            $table->id();
            $table->string('classes');
            $table->integer('teacher');
            $table->string('material');
            $table->string('activity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
        Schema::dropIfExists('classes');
        Schema::dropIfExists('students');
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('student_absences');
        Schema::dropIfExists('teaching_activities');
    }
};
