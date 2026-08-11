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
        Schema::create('daily_lho_reports', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('ph_user')->nullable();
            $table->text('ph_notes')->nullable();
            $table->string('ph_file')->nullable();
            $table->string('ph_handwriting_img')->nullable();
            $table->string('kadep_user')->nullable();
            $table->text('kadep_global_notes')->nullable();
            $table->text('kadep_ph_notes')->nullable();
            $table->string('kadep_file')->nullable();
            $table->string('kadep_handwriting_img')->nullable();
            $table->string('kepsek_user')->nullable();
            $table->text('kepsek_notes')->nullable();
            $table->string('kepsek_file')->nullable();
            $table->string('kepsek_handwriting_img')->nullable();
            $table->string('status')->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_lho_reports');
    }
};
