<?php
namespace App\Services;
use App\Helpers\IdGenerator;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use App\Models\Peralatan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * PeminjamanService
 * Menangani semua business logic terkait peminjaman peralatan:
 *   - Validasi stok sebelum menyimpan
 *   - Simpan header + item dalam satu transaksi
 *   - Routing notif WA ke inventaris yang tepat per gedung
 *   - Proses persetujuan, penolakan, pengembalian
 *
 */
class PeminjamanService
{
    public function __construct(private WhatsAppService $wa){}

    public function ajukan(array $header, array $peralatanIds, array $jumlahArr): Peminjaman
    {
        $this->validasiStok($peralatanIds, $jumlahArr);
        $peminjaman = DB::transaction(function () use ($header, $peralatanIds, $jumlahArr) {
            $peminjaman = Peminjaman::create($header);
            $this->simpanItem($peminjaman, $peralatanIds, $jumlahArr);
            return $peminjaman;
        });
        $this->kirimNotifKeInventaris($peminjaman);
        return $peminjaman;
    }

    public function setujui(Peminjaman $peminjaman, User $inventaris, ?string $catatan = null): void
    {
        $this->pastikanGedungBerwenang($peminjaman, $inventaris);
        DB::transaction(function () use ($peminjaman, $catatan) {
            $peminjaman->loadMissing('items.peralatan');
            foreach ($peminjaman->items as $item) {
                $alat = $item->peralatan;
                if ($item->jumlah > $alat->stok) { 
                    throw new \RuntimeException("Gagal menyetujui. Stok {$alat->nama_peralatan} mendadak tidak mencukupi.");
                }
                $alat->decrement('stok', $item->jumlah);
            }
            $peminjaman->update([
                'status' => 'disetujui',
                'catatan_inventaris' => $catatan,
            ]);
        });
    }

    public function tolak(Peminjaman $peminjaman, User $inventaris, string $alasan): void
    {
        $this->pastikanGedungBerwenang($peminjaman, $inventaris);

        // Karena status 'menunggu' belum memotong stok fisik, penolakan langsung mengubah status saja
        $peminjaman->update([
            'status' => 'ditolak',
            'catatan_inventaris' => $alasan,
        ]);
    }

    public function konfirmasiKembali(Peminjaman $peminjaman, User $inventaris): void
    {
        $this->pastikanGedungBerwenang($peminjaman, $inventaris);
        DB::transaction(function () use ($peminjaman) {
            $peminjaman->loadMissing('items.peralatan');
            foreach ($peminjaman->items as $item) {
                $item->peralatan->increment('stok', $item->jumlah);
            }
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali_aktual' => now()->toDateString(),
            ]);
        });
    }

    public function batalkan(Peminjaman $peminjaman, string $alasan): void
    {
        if (!$peminjaman->isMenunggu()) {
            throw new \RuntimeException('Hanya pengajuan berstatus "Menunggu" yang bisa dibatalkan.');
        }
        DB::transaction(function () use ($peminjaman, $alasan) {
            $peminjaman->update([
                'status' => 'dibatalkan',
                'alasan_batal' => $alasan,
                'dibatalkan_at' => now(),
            ]);
        });
        $peminjaman->load(['items.peralatan', 'user']);
        $itemPerGedung = $peminjaman->items->groupBy(fn($item) => $item->peralatan->gedung);
        foreach ($itemPerGedung as $gedung => $items) {
            $inventaris = \App\Models\User::where('role', 'inventaris')
                ->where('gedung', $gedung)
                ->where('status', 'active')
                ->first();
            if (!$inventaris?->nohp)
                continue;

            $daftarPeralatan = $items->map(function ($item) {
                return "  - {$item->peralatan->nama_peralatan} (x{$item->jumlah})";
            })->join("\n");

            $pesan = $this->wa->templatePeminjamanDibatalkan(
                namaInventaris: $inventaris->nama_user,
                namaOperator: $peminjaman->user->nama_user,
                gedung: $gedung,
                tanggalPinjam: $peminjaman->tanggal_pinjam->format('d/m/Y'),
                tanggalKembali: $peminjaman->tanggal_kembali_rencana->format('d/m/Y'),
                keperluan: $peminjaman->keperluan,
                daftarPeralatan: $daftarPeralatan,
                alasan: $alasan
            );
            $this->wa->kirim($inventaris->nohp, $pesan);
        }
    }

    private function validasiStok(array $peralatanIds, array $jumlahArr): void
    {
        foreach ($peralatanIds as $i => $id) {
            if (empty($id) || empty($jumlahArr[$i]))
                continue;
            $alat = Peralatan::findOrFail($id);
            // Menggunakan properti stok bawaan model
            if ((int) $jumlahArr[$i] > $alat->stok) {
                throw new \RuntimeException(
                    "Stok {$alat->nama_peralatan} tidak mencukupi. Tersedia: {$alat->stok}, diminta: {$jumlahArr[$i]}."
                );
            }
        }
    }

    private function simpanItem(Peminjaman $peminjaman, array $peralatanIds, array $jumlahArr): void
    {
        foreach ($peralatanIds as $i => $id) {
            if (empty($id) || empty($jumlahArr[$i]))
                continue;
            PeminjamanItem::create([
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'id_peralatan' => $id,
                'jumlah' => $jumlahArr[$i],
            ]);
        }
    }

    private function kirimNotifKeInventaris(Peminjaman $peminjaman): void
    {
        $peminjaman->load(['items.peralatan', 'user']);
        $itemPerGedung = $peminjaman->items->groupBy(fn($item) => $item->peralatan->gedung);
        foreach ($itemPerGedung as $gedung => $items) {
            $inventaris = User::where('role', 'inventaris')
                ->where('gedung', $gedung)
                ->where('status', 'active')
                ->first();
            if (!$inventaris?->nohp)
                continue;
            $daftarPeralatan = $items->map(function ($item) {
                return "  - {$item->peralatan->nama_peralatan} (x{$item->jumlah})";
            })->join("\n");
            $pesan = $this->wa->templatePeminjamanBaru(
                namaInventaris: $inventaris->nama_user,
                namaOperator: $peminjaman->user->nama_user,
                gedung: $gedung,
                tanggalPinjam: $peminjaman->tanggal_pinjam->format('d/m/Y'),
                tanggalKembali: $peminjaman->tanggal_kembali_rencana->format('d/m/Y'),
                keperluan: $peminjaman->keperluan,
                daftarPeralatan: $daftarPeralatan,
            );
            $this->wa->kirim($inventaris->nohp, $pesan);
        }
    }

    private function pastikanGedungBerwenang(Peminjaman $peminjaman, User $inventaris): void
    {
        $peminjaman->loadMissing('items.peralatan');
        $gedungTerlibat = $peminjaman->items->pluck('peralatan.gedung')->unique()->values();
        if (!$gedungTerlibat->contains($inventaris->gedung)) {
            throw new \RuntimeException(
                "Anda tidak berwenang memproses pengajuan ini. Pengajuan ini tidak mencakup peralatan dari {$inventaris->gedung}."
            );
        }
    }
}
