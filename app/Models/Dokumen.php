<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Dokumen extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'dokumen';

    // Define key type as string for UUID
    protected $keyType = 'string';

    // Disable auto-incrementing as we're using UUID
    public $incrementing = false;

    protected $fillable = [
        'judul_penelitian',
        'abstrak_penelitian',
        'metode_penelitian',
        'total_anggaran',
        'sumber_dana',
        'lokasi_penelitian',
        'waktu_mulai',
        'waktu_selesai',
        'spesifikasi_outcome',
        'file_proposal_pdf',
        'file_proposal_word',
        'jenis_dokumen_id',
        'user_id',
        'reviewer_id',
        'status',
        'catatan_reviewer',
        'tanggal_review',
        'tanggal_submit',
        'nilai'
    ];

    protected $casts = [
        'id' => 'string',
        'waktu_mulai' => 'date',
        'waktu_selesai' => 'date',
        'total_anggaran' => 'decimal:2',
        'tanggal_review' => 'datetime',
        'tanggal_submit' => 'datetime',
        'nilai' => 'integer',
        'jenis_dokumen_id' => 'string',
        'user_id' => 'string',
        'reviewer_id' => 'string'
    ];


    const STATUS_SUBMITTED = 'submitted';
    const STATUS_REVISI = 'revisi';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_BERHASIL = 'berhasil';


    public function jenisDokumen(): BelongsTo
    {
        return $this->belongsTo(JenisDokumen::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }


    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeInPeriod($query, $start, $end)
    {
        return $query->whereBetween('waktu_mulai', [$start, $end])
            ->orWhereBetween('waktu_selesai', [$start, $end]);
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    public function scopeRevisi($query)
    {
        return $query->where('status', self::STATUS_REVISI);
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', self::STATUS_DITOLAK);
    }

    public function scopeBerhasil($query)
    {
        return $query->where('status', self::STATUS_BERHASIL);
    }

    public function scopeByReviewer($query, $reviewerId)
    {
        return $query->where('reviewer_id', $reviewerId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByJenisDokumen($query, $jenisDokumenId)
    {
        return $query->where('jenis_dokumen_id', $jenisDokumenId);
    }


    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isRevisi(): bool
    {
        return $this->status === self::STATUS_REVISI;
    }

    public function isDitolak(): bool
    {
        return $this->status === self::STATUS_DITOLAK;
    }

    public function isBerhasil(): bool
    {
        return $this->status === self::STATUS_BERHASIL;
    }

    public function hasReviewer(): bool
    {
        return !is_null($this->reviewer_id);
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_REVISI => 'Revisi',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_BERHASIL => 'Berhasil',
            default => 'Unknown'
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            self::STATUS_SUBMITTED => 'info',
            self::STATUS_REVISI => 'warning',
            self::STATUS_DITOLAK => 'danger',
            self::STATUS_BERHASIL => 'success',
            default => 'secondary'
        };
    }

    public function getDurationInMonths(): int
    {
        return $this->waktu_mulai->diffInMonths($this->waktu_selesai);
    }

    public function getFormattedTotalAnggaran(): string
    {
        return 'Rp ' . number_format($this->total_anggaran, 0, ',', '.');
    }
}