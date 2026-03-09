<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Penjadwalan extends Model
{
    protected $table = 'penjadwalan';
    protected $primaryKey = 'id_penjadwalan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'id_penjadwalan',
        'judul_kegiatan',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'platform',
        'keterangan',
        'id_user',
        'id_pemateri',
    ];
    protected $casts = [
        'tanggal' => 'date:Y-m-d', 
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user')
                    ->withDefault(['nama_user' => '-']);
    }
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_penjadwalan', 'id_penjadwalan');
    }
    public function pemateri()
    {
        return $this->belongsTo(User::class, 'id_pemateri', 'id_user')
                    ->withDefault(['nama_user' => '-']);
    }
    public function getStartDateTimeAttribute()
    {
        if (!$this->tanggal || !$this->waktu_mulai) return null;
        $time = strlen($this->waktu_mulai) === 5 ? $this->waktu_mulai . ':00' : $this->waktu_mulai;
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->tanggal->format('Y-m-d') . ' ' . $time)
            ->timezone('Asia/Jakarta');
    }
    public function getEndDateTimeAttribute()
    {
        if (!$this->tanggal || !$this->waktu_selesai) return null;
        $time = strlen($this->waktu_selesai) === 5 ? $this->waktu_selesai . ':00' : $this->waktu_selesai;
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->tanggal->format('Y-m-d') . ' ' . $time)
            ->timezone('Asia/Jakarta');
    }
    public function scopeBentrok($query, $id_user, $tanggal, $mulai, $selesai, $excludeId = null)
    {
        return $query->where('id_user', $id_user)
            ->whereDate('tanggal', $tanggal)
            ->when($excludeId, fn($q) => $q->where('id_penjadwalan', '!=', $excludeId))
            ->where(function ($q) use ($mulai, $selesai) {
                $q->whereBetween('waktu_mulai', [$mulai, $selesai])
                  ->orWhereBetween('waktu_selesai', [$mulai, $selesai])
                  ->orWhere(function ($qq) use ($mulai, $selesai) {
                      $qq->where('waktu_mulai', '<=', $mulai)
                         ->where('waktu_selesai', '>=', $selesai);
                  });
            });
    }
    public function jadwalPeralatan()
    {
        return $this->hasMany(JadwalPeralatan::class,'id_penjadwalan','id_penjadwalan');
    }
}