<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanDokumentasi extends Model
{

    protected $table = 'laporan_dokumentasi';
    protected $fillable = [
        'laporan_harian_id',
        'file'
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanHarian::class);
    }

}
