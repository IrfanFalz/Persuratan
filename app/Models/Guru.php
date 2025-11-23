<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    public $timestamps = true;

    protected $fillable = [
        'nip',
        'nama',
        'no_telp',
        'id_pengguna',
    ];
}
