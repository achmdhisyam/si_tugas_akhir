<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi mahasiswa ke skripsinya (1-1).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function skripsi()
    {
        return $this->hasOne(Skripsi::class, 'user_id');
    }

    /**
     * Relasi pembimbing ke skripsi bimbingannya (1-N).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bimbinganSkripsi()
    {
        return $this->hasMany(Skripsi::class, 'dosen_id');
    }

    /**
     * Relasi penguji ke jadwal sidang ujiannya (1-N).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ujianSidang()
    {
        return $this->hasMany(JadwalSidang::class, 'penguji_id');
    }
}
