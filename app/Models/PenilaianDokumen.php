<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianDokumen extends Model
{
    use HasFactory;

    protected $table = 'penilaian_dokumen';

    protected $fillable = [
        'dokumen_id',
        'kriteria_penilaian_id',
        'skor',
        'nilai',
        'justifikasi'
    ];

    protected $casts = [
        'dokumen_id' => 'string', // Karena dokumen pakai UUID
        'kriteria_penilaian_id' => 'integer', // Karena kriteria pakai auto increment
        'skor' => 'decimal:2',
        'nilai' => 'decimal:2'
    ];

    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(KriteriaPenilaian::class, 'kriteria_penilaian_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($penilaian) {
            $penilaian->nilai = $penilaian->skor * $penilaian->kriteria->bobot;
        });
    }
}