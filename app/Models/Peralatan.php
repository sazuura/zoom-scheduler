<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    protected $table = 'peralatan';
    protected $primaryKey = 'id_peralatan';
    public $incrementing = false; 
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_peralatan',
        'nama_peralatan',
        'kondisi',
        'stok',
    ];
}
