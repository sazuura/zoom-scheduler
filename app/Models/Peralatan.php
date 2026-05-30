<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peralatan extends Model
{
    protected $table      = 'peralatan';
    protected $primaryKey = 'id_peralatan';
    public $incrementing  = false;
    protected $keyType    = 'string';
    public $timestamps    = false;

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

    /**
     * Hitung jumlah yang sedang dipakai — hanya jadwal yang belum selesai (tanggal >= hari ini).
     *
     * SEBELUMNYA: menghitung SEMUA JadwalPeralatan termasuk jadwal lampau,
     * sehingga stok tampak selalu berkurang meski jadwalnya sudah selesai.
     */
    public function getDipakaiAttribute(): int
    {
        return $this->jadwalPeralatan()
            ->whereHas('penjadwalan', function ($query) {
                $query->whereDate('tanggal', '>=', Carbon::today());
            })
            ->sum('jumlah');
    }

    /**
     * Stok yang benar-benar tersedia untuk dipakai.
     */
    public function getStokTersediaAttribute(): int
    {
        $tersedia = $this->stok
            - ($this->rusak ?? 0)
            - ($this->perbaikan ?? 0)
            - $this->dipakai;

        return max($tersedia, 0);
    }

    public function getStatusAttribute(): string
    {
        return $this->stok_tersedia > 0 ? 'Tersedia' : 'Tidak Tersedia';
    }
}
