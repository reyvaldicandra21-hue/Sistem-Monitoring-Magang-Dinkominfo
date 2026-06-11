<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaians';

    protected $fillable = [
        'peserta_pkl_id',

        // 🔥 penilaian sikap
        'disiplin',
        'tanggung_jawab',
        'kerjasama',
        'etika',
        'inisiatif',

        // hasil
        'nilai_akhir',
        'predikat',
        'catatan',
    ];

    // ================= RELASI =================

    public function pesertaPkl()
    {
        return $this->belongsTo(PesertaPKL::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

    // ================= HELPER =================

    // 🔥 otomatis hitung rata-rata
    public function getRataNilaiAttribute()
    {
        return collect([
            $this->disiplin,
            $this->tanggung_jawab,
            $this->kerjasama,
            $this->etika,
            $this->inisiatif,
        ])->avg();
    }
}
