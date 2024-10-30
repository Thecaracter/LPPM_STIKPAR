<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaTim extends Model
{
    use HasFactory;
    protected $table = 'anggota_tim';
    protected $fillable = [
        'nama',
        'id_tim'
    ];
    public function tim()
    {
        return $this->belongsTo(User::class, 'id_tim');
    }
    public function scopeOfTim($query, $timId)
    {
        return $query->where('id_tim', $timId);
    }
}
