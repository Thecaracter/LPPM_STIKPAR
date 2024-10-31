<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul_penelitian');
            $table->text('abstrak_penelitian');
            $table->text('metode_penelitian');
            $table->decimal('total_anggaran', 15, 2);
            $table->string('sumber_dana');
            $table->string('lokasi_penelitian');
            $table->date('waktu_mulai');
            $table->date('waktu_selesai');
            $table->text('spesifikasi_outcome');
            $table->string('file_proposal_pdf');
            $table->string('file_proposal_word');
            $table->foreignUuid('jenis_dokumen_id')->constrained('jenis_dokumen')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', [
                'submitted',
                'revisi',
                'ditolak',
                'berhasil'
            ])->default('submitted');
            $table->text('catatan_reviewer')->nullable();
            $table->timestamp('tanggal_review')->nullable();
            $table->timestamp('tanggal_submit')->nullable();
            $table->integer('nilai')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('judul_penelitian');
            $table->index('status');
            $table->index(['waktu_mulai', 'waktu_selesai']);
            $table->index('reviewer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};