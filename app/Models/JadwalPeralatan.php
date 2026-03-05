<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPeralatan extends Model
{
    protected $table = 'jadwal_peralatan';
    protected $primaryKey = 'id_jadwal_alat';
    public $incrementing = false; 

    protected $fillable = [
        'id_penjadwalan',
        'id_peralatan',
        'jumlah',
        'status_pemasangan',
        'created_at',
        'updated_at'
    ];
    public function jadwal()
    {
        return $this->belongsTo(Penjadwalan::class, 'id_penjadwalan');
    }
    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'id_peralatan');
    }
}