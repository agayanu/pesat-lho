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
        if (!Schema::hasTable('studentday_exited_students')) {
            Schema::create('studentday_exited_students', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id')->nullable();
                $table->unsignedBigInteger('studentday_id');
                $table->string('id_siswa');
                $table->string('student_name');
                $table->string('student_class');
                $table->string('studentday_name');
                $table->date('exit_date');
                $table->string('reason');
                $table->text('notes')->nullable();
                $table->string('user');
                $table->timestamps();

                $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
                $table->foreign('studentday_id')->references('id')->on('studentdays')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentday_exited_students');
    }
};
