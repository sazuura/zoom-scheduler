<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi';
    protected $primaryKey = 'id_dokumentasi';
    protected $fillable = [
        'id_absensi',
        'foto',
        'keterangan'
    ];
    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'id_absensi');
    }
}