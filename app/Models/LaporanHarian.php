<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHarian extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'laporan_harians';

    protected $fillable = [
        'uuid',
        'user_id',
        'peserta_pkl_id',
        'tanggal',
        'kegiatan',
        'hasil',
        'kendala',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pesertaPkl()
    {
        return $this->belongsTo(PesertaPKL::class, 'peserta_pkl_id');
    }

    public function dokumentasi()
    {
        return $this->hasMany(LaporanDokumentasi::class, 'laporan_harian_id');
    }

    public function verifikasis()
    {
        return $this->hasMany(LogVerifikasi::class, 'laporan_harian_id');
    }

    public function verifikasiTerakhir()
    {
        return $this->hasOne(LogVerifikasi::class, 'laporan_harian_id')
                    ->latestOfMany();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
