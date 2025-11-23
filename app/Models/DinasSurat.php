<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DinasSurat extends Model
{
    protected $table = 'dinas_surat';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kode_dinas',
        'nama_dinas',
    ];
}
