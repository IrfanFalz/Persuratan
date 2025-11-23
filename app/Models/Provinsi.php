<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    // Use existing table name in your DB
    protected $table = 'provinsi_surat';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode_provinsi',
        'nama_provinsi',
    ];
}
