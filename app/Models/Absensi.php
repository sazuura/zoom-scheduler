<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_user',
        'id_penjadwalan',
        'tanggal',
        'status',
        'keterangan'
    ];
    protected $casts = [
        'tanggal' => 'date'
    ];
    public function user() {
        return $this->belongsTo(User::class,'id_user','id_user');
    }
    public function penjadwalan() {
        return $this->belongsTo(Penjadwalan::class,'id_penjadwalan','id_penjadwalan');
    }
    public function dokumentasi(){
        return $this->hasMany(Dokumentasi::class, 'id_absensi');
    }
}
