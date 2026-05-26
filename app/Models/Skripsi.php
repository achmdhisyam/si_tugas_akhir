<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skripsi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'judul',
        'file_skripsi',
        'status',
        'alasan_penolakan',
        'progress',
        'dosen_id',
        'dosen_id_2',
        'file_draft_final',
        'file_revisi',
        'status_revisi',
        'acc_pembimbing_1',
        'acc_pembimbing_2',
        'acc_penguji_1',
        'acc_penguji_2',
    ];

    /**
     * Relasi ke mahasiswa pemilik skripsi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke dosen pembimbing 1 skripsi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Relasi ke dosen pembimbing 2 skripsi
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pembimbing2()
    {
        return $this->belongsTo(User::class, 'dosen_id_2');
    }

    /**
     * Relasi ke bimbingan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bimbingans()
    {
        return $this->hasMany(Bimbingan::class, 'skripsi_id');
    }

    /**
     * Relasi ke jadwal sidang
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jadwalSidangs()
    {
        return $this->hasMany(JadwalSidang::class, 'skripsi_id');
    }
}
