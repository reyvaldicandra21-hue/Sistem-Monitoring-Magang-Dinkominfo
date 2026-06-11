<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasPengumpulan extends Model
{

    protected $fillable=[
        'tugas_id',
        'peserta_pkl_id',
        'file',
        'catatan',
        'tanggal_kumpul',
        'status',
        'nilai',
        'komentar_pembimbing'
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function pesertaPkl()
    {
        return $this->belongsTo(PesertaPKL::class,'peserta_pkl_id');
    }

}
