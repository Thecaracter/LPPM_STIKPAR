<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokumen extends Model
{
    use SoftDeletes, HasFactory;

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
        'tanggal_submit'
    ];

    protected $casts = [
        'waktu_mulai' => 'date',
        'waktu_selesai' => 'date',
        'total_anggaran' => 'decimal:2',
        'tanggal_review' => 'datetime',
        'tanggal_submit' => 'datetime'
    ];

    // Relations
    public function pengaju()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class);
    }
}