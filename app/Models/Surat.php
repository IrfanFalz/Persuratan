<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat';
    protected $primaryKey = 'id_surat';
    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'id_persetujuan',
        'id_template',
        'status_berkas',
        'nomor_surat',
        'dibuat_pada'
    ];

    protected $casts = [
        'dibuat_pada' => 'datetime',
    ];

    /**
     * Relasi ke pengguna (pemohon)
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Relasi ke persetujuan
     */
    public function persetujuan()
    {
        return $this->belongsTo(Persetujuan::class, 'id_persetujuan', 'id_persetujuan');
    }

    /**
     * Relasi ke template surat
     * WAJIB untuk memperbaiki error "undefined relationship [template]"
     */
    public function template()
    {
        return $this->belongsTo(TemplateSurat::class, 'id_template', 'id');
        // Jika PK TemplateSurat = id, ganti menjadi:
        // return $this->belongsTo(TemplateSurat::class, 'id_template', 'id');
    }

    /**
     * Relasi ke surat dispensasi
     */
    public function suratDispensasi()
    {
        return $this->hasOne(SuratDispensasi::class, 'id_surat', 'id_surat');
    }

    /**
     * Relasi ke SPT (Surat Perintah Tugas)
     */
    public function suratPerintahTugas()
    {
        return $this->hasOne(SuratPerintahTugas::class, 'id_surat', 'id_surat');
    }

    /**
     * Relasi ke notifikasi
     */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_surat', 'id_surat');
    }
}
