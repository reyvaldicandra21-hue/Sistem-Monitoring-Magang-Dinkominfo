<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    protected $table = 'pembimbings';

    protected $fillable = [
        'user_id',
        'nama',
        'jabatan',
        'divisi_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penilaians()
{
    return $this->hasMany(Penilaian::class);
}

    public function pesertaPkls()
    {
        return $this->hasMany(PesertaPkl::class, 'pembimbing_id');
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
}

