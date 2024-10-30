<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianDokumen extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'penilaian_dokumen';

    protected $fillable = [
        'dokumen_id',
        'kriteria_penilaian_id',
        'skor',
        'nilai',
        'justifikasi'
    ];

    protected $casts = [
        'skor' => 'decimal:2',
        'nilai' => 'decimal:2'
    ];

    // Basic Relationships
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(KriteriaPenilaian::class, 'kriteria_penilaian_id');
    }

    // Simple method to calculate nilai
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($penilaian) {
            $penilaian->nilai = $penilaian->skor * $penilaian->kriteria->bobot;
        });
    }
}