<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';   
    public $incrementing = true;            
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'nama',
        'password',
        'role',
        'no_telp',
        'nip',
    ];

    protected $hidden = [
        'password',
        'remember_token', 
    ];

    public function surat()
    {
        return $this->hasMany(Surat::class, 'id_pengguna', 'id_pengguna');
    }

    public function persetujuan()
    {
        return $this->hasMany(Persetujuan::class, 'id_pengguna', 'id_pengguna');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'id_pengguna', 'id_pengguna');
    }
}
