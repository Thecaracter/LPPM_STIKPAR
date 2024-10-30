<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['user', 'reviewer', 'admin'])->default('user');
            $table->string('name');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nik')->unique();
            $table->string('nidn_nuptk')->nullable()->unique();
            $table->enum('jabatan_akademik', [
                'Asisten Ahli',
                'Lektor',
                'Lektor Kepala',
                'Guru Besar',
                'Belum Memiliki Jabatan Akademik'
            ]);
            $table->string('bidang_keahlian');
            $table->string('program_studi');
            $table->text('alamat_domisili');
            $table->string('no_hp');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
