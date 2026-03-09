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
        'stok',
        'lokasi_penyimpanan',
        'rusak',
        'perbaikan',
        'keterangan',
    ];
    public function jadwalPeralatan()
    {
        return $this->hasMany(JadwalPeralatan::class, 'id_peralatan');
    }
    public function getDipakaiAttribute()
    {
        return $this->jadwalPeralatan()->sum('jumlah');
    }
    public function getStokTersediaAttribute()
    {
        $stok = $this->stok 
                - $this->rusak 
                - $this->perbaikan 
                - $this->dipakai;

        return max($stok, 0);
    }
    public function getStatusAttribute()
    {
        return $this->stok_tersedia > 0 ? 'Tersedia' : 'Tidak Tersedia';
    }
}