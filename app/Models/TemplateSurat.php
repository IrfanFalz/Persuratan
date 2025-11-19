<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';

    protected $fillable = [
        'nama_template',
        'deskripsi',
        'tipe_surat',
        'kop_surat',
        'isi_template'
    ];
}

