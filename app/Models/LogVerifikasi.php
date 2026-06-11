<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogVerifikasi extends Model
{
    protected $table = 'log_verifikasi';

    protected $fillable = [
        'laporan_harian_id',
        'pembimbing_id',
        'status',
        'catatan_pembimbing',
        'verified_at',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanHarian::class, 'laporan_harian_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }
}
