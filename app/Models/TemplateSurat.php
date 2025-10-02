<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';
    protected $primaryKey = 'id'; 
    public $timestamps = true;    
    protected $fillable = [
        'nama',
        'slug',
        'html_content',
    ];
}
