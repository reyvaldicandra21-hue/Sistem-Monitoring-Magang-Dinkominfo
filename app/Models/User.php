<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // role sudah mass assignable
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_PESERTA = 'pesertapkl';
    const ROLE_PEMBIMBING = 'pembimbing';


    // Semua role (key = enum DB, value = label)
    public static function roles(): array
    {
        return [
            self::ROLE_ADMIN      => 'Admin',
            self::ROLE_PESERTA    => 'PesertaPKL',
            self::ROLE_PEMBIMBING => 'Pembimbing',
        ];
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Relasi
    public function pesertaPkl()
    {
        return $this->hasOne(PesertaPkl::class);
    }

    public function pembimbing()
    {
        return $this->hasOne(Pembimbing::class);
    }
}
