<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metric_id')->constrained('metrics')->cascadeOnDelete();
            $table->decimal('nilai_target', 15, 2);
            $table->enum('periode_tipe', ['harian', 'mingguan', 'bulanan', 'tahunan']);
            $table->date('periode_mulai');
            $table->date('periode_selesai')->nullable();
            $table->foreignId('set_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};