<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasFile extends Model
{
    protected $table = 'tugas_files';

    protected $fillable = [
        'tugas_id',
        'file_path',
        'original_name',
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }
}
