<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table      = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';

    protected $fillable = [
        'id_user',
        'id_peralatan',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'keperluan',
        'status',
        'catatan_inventaris',
    ];

    protected $casts = [
        'tanggal_pinjam'           => 'date',
        'tanggal_kembali_rencana'  => 'date',
        'tanggal_kembali_aktual'   => 'date',
    ];

    // ─── Relasi ──────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function peralatan()
    {
        return $this->belongsTo(Peralatan::class, 'id_peralatan', 'id_peralatan');
    }

    // ─── Helper status ───────────────────────────────────────────────────────

    public function isMenunggu(): bool   { return $this->status === 'diajukan'; }
    public function isDisetujui(): bool  { return $this->status === 'disetujui'; }
    public function isDitolak(): bool    { return $this->status === 'ditolak'; }
    public function isDikembalikan(): bool { return $this->status === 'dikembalikan'; }

    // Label & warna badge untuk view
    public function getBadgeAttribute(): array
    {
        return match($this->status) {
            'diajukan'     => ['class' => 'badge-warning', 'label' => 'Menunggu'],
            'disetujui'    => ['class' => 'badge-active',  'label' => 'Disetujui'],
            'ditolak'      => ['class' => 'badge-danger',  'label' => 'Ditolak'],
            'dikembalikan' => ['class' => 'badge-info',    'label' => 'Dikembalikan'],
            default        => ['class' => '',               'label' => $this->status],
        };
    }
}
