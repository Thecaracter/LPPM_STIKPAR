<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
        'name',
        'tempat_lahir',
        'tanggal_lahir',
        'nik',
        'nidn_nuptk',
        'jabatan_akademik',
        'bidang_keahlian',
        'program_studi',
        'alamat_domisili',
        'no_hp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir' => 'date',
    ];

    // Basic Relations
    public function dokumenDiajukan()
    {
        return $this->hasMany(Dokumen::class, 'user_id');
    }

    public function dokumenDireview()
    {
        return $this->hasMany(Dokumen::class, 'reviewer_id');
    }

    public function anggotaTim()
    {
        return $this->hasMany(AnggotaTim::class, 'id_tim');
    }

    // Basic Role Checks
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isReviewer()
    {
        return $this->role === 'reviewer';
    }

    // Basic Constants
    public static function getRoleOptions()
    {
        return ['user', 'reviewer', 'admin'];
    }

    public static function getJabatanAkademikOptions()
    {
        return [
            'Asisten Ahli',
            'Lektor',
            'Lektor Kepala',
            'Guru Besar',
            'Belum Memiliki Jabatan Akademik'
        ];
    }
}