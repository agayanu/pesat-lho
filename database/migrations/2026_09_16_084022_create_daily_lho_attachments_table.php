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
        Schema::create('daily_lho_attachments', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('daily_lho_report_id')->nullable();
            $table->enum('role', ['PH', 'KADEP', 'KEPSEK']);
            $table->string('file_label');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->default('document'); // image or document
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('daily_lho_report_id')->references('id')->on('daily_lho_reports')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_lho_attachments');
    }
};
