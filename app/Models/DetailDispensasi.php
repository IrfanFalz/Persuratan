<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailDispensasi extends Model
{
    //
    protected $table = 'detail_dispensasi';
    protected $primaryKey = 'id_Ddetail';
    public $timestamps = false;

    public function suratDispensasi()
    {
        return $this->belongsTo(SuratDispensasi::class, 'id_sd');
    }
}
