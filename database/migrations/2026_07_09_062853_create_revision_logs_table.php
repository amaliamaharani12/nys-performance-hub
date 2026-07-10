<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revision_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actual_id')->constrained('actuals')->cascadeOnDelete();
            $table->foreignId('revised_by')->constrained('users');
            $table->decimal('nilai_lama', 15, 2);
            $table->decimal('nilai_baru', 15, 2);
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revision_logs');
    }
};