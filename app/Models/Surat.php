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
        'status_berkas',
        'dibuat_pada',
        'id_template'
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function template() {
        return $this->belongsTo(TemplateSurat::class, 'id_template', 'id');
    }

    public function persetujuan()
    {
        return $this->hasOne(Persetujuan::class, 'id_surat', 'id_surat');
    }

    public function suratDispensasi()
    {
        return $this->hasOne(SuratDispensasi::class, 'id_surat', 'id_surat');
    }

    public function suratPerintahTugas()
    {
        return $this->hasOne(SuratPerintahTugas::class, 'id_surat', 'id_surat');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_surat', 'id_surat');
    }

    public function guruTerkait()
    {
        if ($this->suratDispensasi && method_exists($this->suratDispensasi, 'guruDispensasi')) {
            return $this->suratDispensasi->guruDispensasi();
        }

        if ($this->suratPerintahTugas && method_exists($this->suratPerintahTugas, 'guruPerintahTugas')) {
            return $this->suratPerintahTugas->guruPerintahTugas();
        }

        return collect(); 
    }
}
