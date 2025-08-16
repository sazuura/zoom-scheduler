<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    protected $table = 'penjadwalan';
    protected $primaryKey = 'id_penjadwalan'; 
    public $incrementing = false;
    public $timestamps = false;   
}
