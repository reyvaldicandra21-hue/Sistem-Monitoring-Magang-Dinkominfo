<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisi';

    protected $fillable = [
        'nama_divisi'
    ];

    public function pesertaPkl()
    {
        return $this->hasMany(PesertaPkl::class);
    }

    public function pembimbing()
    {
        return $this->hasMany(Pembimbing::class);
    }
}
