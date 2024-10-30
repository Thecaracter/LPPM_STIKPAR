<?php
// Create new migration: 2024_10_31_000001_create_kriteria_penilaian_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kriteria_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('jenis_dokumen_id')->constrained('jenis_dokumen')->onDelete('cascade');
            $table->string('nama_kriteria');
            $table->decimal('bobot', 5, 2);
            $table->timestamps();
        });

        Schema::create('penilaian_dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumen_id')->constrained('dokumen')->onDelete('cascade');
            $table->foreignId('kriteria_penilaian_id')->constrained('kriteria_penilaian')->onDelete('cascade');
            $table->decimal('skor', 5, 2);
            $table->decimal('nilai', 8, 2);
            $table->text('justifikasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_dokumen');
        Schema::dropIfExists('kriteria_penilaian');
    }
};