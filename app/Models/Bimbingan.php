<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bimbingan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'skripsi_id',
        'file_progres',
        'tanggal',
        'catatan',
        'status',
        'catatan_dosen',
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
}
