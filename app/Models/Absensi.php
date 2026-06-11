<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\PesertaPkl;
use App\Models\User;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'user_id',
        'peserta_pkl_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'alasan',
        'bukti',
        'keterangan',
        'foto',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pesertaPkl()
    {
        return $this->belongsTo(PesertaPKL::class);
    }

    public function scopeHariIni($query, $pesertaId)
    {
        return $query
            ->where('peserta_pkl_id',$pesertaId)
            ->whereDate('tanggal',Carbon::today());
    }

    public function getJamPulangDisplayAttribute()
    {
        return $this->jam_pulang ?? '16:00';
    }

    public function user()
{
    return $this->belongsTo(User::class);
}
}
