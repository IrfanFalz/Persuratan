<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratDispensasi extends Model
{
    protected $table = 'surat_dispensasi';
    protected $primaryKey = 'id_sd';
    public $timestamps = false;

    protected $fillable = [
        'id_surat',
        'id_persetujuan',
        'keperluan',
        'tempat',
        'tanggal',
        'jam',
        'hari'
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat', 'id_surat');
    }

    public function persetujuan()
    {
        return $this->belongsTo(Persetujuan::class, 'id_persetujuan', 'id_persetujuan');
    }

    public function detail()
    {
        return $this->hasMany(DetailDispensasi::class, 'id_sd', 'id_sd');
    }
}
