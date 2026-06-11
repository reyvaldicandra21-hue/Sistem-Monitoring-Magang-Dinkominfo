<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryDivisi extends Model
{
    protected $table = 'history_divisi';

    protected $fillable = [
        'peserta_pkl_id',
        'divisi_id_lama',
        'divisi_id_baru',
        'keterangan',
        'tanggal_perubahan',
    ];

    protected $casts = [
        'tanggal_perubahan' => 'datetime',
    ];

    // ================= RELASI =================

    public function pesertaPkl()
    {
        return $this->belongsTo(PesertaPkl::class);
    }

    public function divisiLama()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id_lama');
    }

    public function divisiBaru()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id_baru');
    }
}
