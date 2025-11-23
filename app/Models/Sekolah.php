<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    // Use existing table name in your DB
    protected $table = 'sekolah_surat';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode_sekolah',
        'nama_sekolah',
    ];
}
