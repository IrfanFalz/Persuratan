<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSpt extends Model
{
    //
    protected $table = 'detai_spt'; 
    protected $primaryKey = 'id_Tdetail';
    public $timestamps = false;

    public function suratPerintahTugas()
    {
        return $this->belongsTo(SuratPerintahTugas::class, 'id_spt');
    }
}
