<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PesertaPkl extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'user_id',
        'pembimbing_id',

        'divisi_id',

        'tanggal_mulai',
        'tanggal_selesai',

        'jenis',
        'asal_institusi',
        'jurusan',
        'no_hp',
        'status',
    ];

    // ================= RELASI =================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }



    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }

    public function tugas()
    {
    return $this->belongsToMany(Tugas::class, 'peserta_tugas');
    }

    public function laporanHarian()
    {
        return $this->hasMany(LaporanHarian::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function penilaian()
    {
        return $this->hasOne(Penilaian::class);
    }

    public function historyDivisi()
    {
        return $this->hasMany(HistoryDivisi::class)->orderByDesc('tanggal_perubahan');
    }

    // ================= STATUS OTOMATIS =================

public function getStatusAktifAttribute()
{
    $today = Carbon::today();

    if ($this->tanggal_mulai > $today) {
        return 'pending';
    }

    if ($this->tanggal_mulai <= $today && $this->tanggal_selesai >= $today) {
        return 'aktif';
    }

    if ($this->tanggal_selesai < $today) {
        return 'selesai';
    }

    return 'pending';
}
}
