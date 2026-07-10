<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actuals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metric_id')->constrained('metrics')->cascadeOnDelete();
            $table->date('periode');
            $table->decimal('nilai_actual', 15, 2);
            $table->foreignId('input_by')->constrained('users');
            $table->enum('sumber', ['upload', 'manual'])->default('manual');
            $table->enum('status', ['draft', 'pending', 'approved'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actuals');
    }
};