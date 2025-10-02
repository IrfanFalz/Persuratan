<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    public $timestamps = false; 

    protected $fillable = [
        'id_surat',
        'id_pengguna',
        'pesan',
        'status',
        'created_at',
        'dibaca'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'dibaca' => 'datetime',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat', 'id_surat');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
