<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    use HasUuid;

    protected $table = 'pembimbings';

    protected $fillable = [
        'uuid',
        'user_id',
        'nama',
        'jabatan',
        'divisi_id',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

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

