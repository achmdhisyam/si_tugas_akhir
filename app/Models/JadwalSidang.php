<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSidang extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'skripsi_id',
        'tanggal',
        'jenis',
        'nilai',
        'penguji_1_id',
        'penguji_2_id',
        'ruangan',
        'status',
        'status_kelulusan',
    ];

    /**
     * Relasi ke skripsi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skripsi()
    {
        return $this->belongsTo(Skripsi::class, 'skripsi_id');
    }

    /**
     * Relasi ke dosen penguji 1.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penguji1()
    {
        return $this->belongsTo(User::class, 'penguji_1_id');
    }

    /**
     * Relasi ke dosen penguji 2.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function penguji2()
    {
        return $this->belongsTo(User::class, 'penguji_2_id');
    }
}
