<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KriteriaPenilaian extends Model
{
    use HasFactory;

    protected $table = 'kriteria_penilaian';

    protected $fillable = [
        'jenis_dokumen_id',
        'nama_kriteria',
        'bobot'
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'jenis_dokumen_id' => 'string'
    ];

    // Relations
    public function jenisDokumen(): BelongsTo
    {
        return $this->belongsTo(JenisDokumen::class, 'jenis_dokumen_id');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(PenilaianDokumen::class);
    }

    // Scopes
    public function scopeForJenisDokumen($query, $jenisDokumenId)
    {
        return $query->where('jenis_dokumen_id', $jenisDokumenId);
    }

    // Methods
    public function hitungNilai($skor)
    {
        return $this->bobot * $skor;
    }

    public function validasiSkor($skor): bool
    {
        return $skor >= 0 && $skor <= 10;
    }

    // Static Methods
    public static function getTotalBobot($jenisDokumenId)
    {
        return static::where('jenis_dokumen_id', $jenisDokumenId)
            ->sum('bobot');
    }
}