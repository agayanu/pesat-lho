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
        if (!Schema::hasTable('student_absences')) {
            Schema::create('student_absences', function (Blueprint $table) {
                $table->id();
                $table->date('date');
                $table->string('class_code');
                $table->integer('jam_ke')->default(1);
                $table->unsignedBigInteger('student_id');
                $table->enum('status', ['Izin', 'Sakit', 'Alpha']);
                $table->string('user'); // Username / Name guru pencatatan awal
                $table->boolean('is_edited_by_piket')->default(false);
                $table->string('piket_user')->nullable(); // Username guru piket jika diubah
                $table->string('edit_reason')->nullable();
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_absences');
    }
};
