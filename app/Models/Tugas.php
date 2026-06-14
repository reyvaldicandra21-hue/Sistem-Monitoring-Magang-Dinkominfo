<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasUuid;

    protected $table='tugas';

    protected $fillable=[
        'uuid',
        'pembimbing_id',
        'judul',
        'deskripsi',
        'deadline',
        'status',
        'file',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class);
    }

    public function pengumpulan()
    {
        return $this->hasMany(TugasPengumpulan::class);
    }

    public function pesertaPkl()
{
    return $this->belongsToMany(PesertaPkl::class, 'peserta_tugas');
}

    public function files()
    {
        return $this->hasMany(TugasFile::class);
    }
}
