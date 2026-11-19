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
        if (!Schema::hasTable('graduated_students')) {
            Schema::create('graduated_students', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('original_student_id')->nullable();
                $table->string('id_siswa');
                $table->string('name');
                $table->string('gender', 10);
                $table->string('classes');
                $table->string('program')->nullable();
                $table->string('studentday')->nullable();
                $table->date('graduation_date');
                $table->text('notes')->nullable();
                $table->string('user');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('exited_students')) {
            Schema::create('exited_students', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('original_student_id')->nullable();
                $table->string('id_siswa');
                $table->string('name');
                $table->string('gender', 10);
                $table->string('classes');
                $table->string('program')->nullable();
                $table->string('studentday')->nullable();
                $table->date('exit_date');
                $table->string('reason')->nullable();
                $table->text('notes')->nullable();
                $table->string('user');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduated_students');
        Schema::dropIfExists('exited_students');
    }
};
