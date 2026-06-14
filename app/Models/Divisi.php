<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasUuid;

    protected $table = 'divisi';

    protected $fillable = [
        'uuid',
        'nama_divisi'
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function pesertaPkl()
    {
        return $this->hasMany(PesertaPkl::class);
    }

    public function pembimbing()
    {
        return $this->hasMany(Pembimbing::class);
    }
}
