<?php
namespace App\Services;
use App\Helpers\IdGenerator;
use App\Models\Absensi;
use App\Models\JadwalPeralatan;
use App\Models\Peralatan;
use App\Models\Penjadwalan;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * PenjadwalanService
 * Menangani semua business logic terkait jadwal rapat:
 *   - Validasi bentrok waktu per operator
 *   - Simpan jadwal + absensi + peralatan dalam satu transaksi
 *   - Kirim notif WA ke operator yang ditugaskan
 * 
 */
class PenjadwalanService
{
    public function __construct(private WhatsAppService $wa) {}
    /**
     * @param  array  $data         Data jadwal yang sudah divalidasi dari controller
     * @param  array  $operatorIds  ID operator yang ditugaskan
     * @param  array  $peralatanIds ID peralatan yang dipakai
     * @param  array  $jumlahArr    Jumlah per peralatan (index sesuai $peralatanIds)
     * @throws \RuntimeException jika stok peralatan tidak cukup
     */
    public function buat(array $data, array $operatorIds, array $peralatanIds, array $jumlahArr): Penjadwalan
    {
        // Cek bentrok sebelum masuk transaksi
        $this->validasiBentrokOperator($operatorIds, $data['tanggal'], $data['waktu_mulai'], $data['waktu_selesai']);
        return DB::transaction(function () use ($data, $operatorIds, $peralatanIds, $jumlahArr) {
            $jadwal = $this->simpanJadwal($data);
            $this->simpanAbsensi($jadwal, $operatorIds, $data['tanggal']);
            $this->simpanPeralatan($jadwal, $peralatanIds, $jumlahArr);
            $this->kirimNotifKeOperator($jadwal, $operatorIds);

            return $jadwal;
        });
    }
    public function ubah(Penjadwalan $jadwal, array $data, array $operatorIds, array $peralatanIds, array $jumlahArr): Penjadwalan
    {
        return DB::transaction(function () use ($jadwal, $data, $operatorIds, $peralatanIds, $jumlahArr) {
            $jadwal->update($data);
            Absensi::where('id_penjadwalan', $jadwal->id_penjadwalan)->delete();
            $this->simpanAbsensi($jadwal, $operatorIds, $data['tanggal']);
            JadwalPeralatan::where('id_penjadwalan', $jadwal->id_penjadwalan)->delete();
            $this->simpanPeralatan($jadwal, $peralatanIds, $jumlahArr);
            return $jadwal->fresh();
        });
    }
    public function hapus(Penjadwalan $jadwal): void
    {
        $jadwal->delete();
    }
    private function validasiBentrokOperator(array $operatorIds, string $tanggal, string $mulai, string $selesai): void
    {
        foreach ($operatorIds as $id) {
            $bentrok = Penjadwalan::bentrok($tanggal, $mulai, $selesai)
                ->whereHas('absensi', fn($q) => $q->where('id_user', $id))
                ->exists();
            if ($bentrok) {
                $nama = User::find($id)?->nama_user ?? $id;
                throw new \RuntimeException("Operator {$nama} sudah memiliki jadwal di waktu yang sama.");
            }
        }
    }
    private function simpanJadwal(array $data): Penjadwalan
    {
        return Penjadwalan::create(array_merge($data, [
            'id_penjadwalan' => IdGenerator::next(Penjadwalan::class, 'id_penjadwalan', 'PJ'),
        ]));
    }
    private function simpanAbsensi(Penjadwalan $jadwal, array $operatorIds, string $tanggal): void
    {
        foreach ($operatorIds as $id) {
            if (empty($id)) continue;
            Absensi::create([
                'id_penjadwalan' => $jadwal->id_penjadwalan,
                'id_user'        => $id,
                'tanggal'        => $tanggal,
                'status'         => Absensi::STATUS_PENDING,
                'validated'      => false,
            ]);
        }
    }
    private function simpanPeralatan(Penjadwalan $jadwal, array $peralatanIds, array $jumlahArr): void
    {
        foreach ($peralatanIds as $i => $idPeralatan) {
            if (empty($idPeralatan) || empty($jumlahArr[$i])) continue;
            $alat = Peralatan::findOrFail($idPeralatan);
            if ((int) $jumlahArr[$i] > $alat->stok_tersedia) {
                throw new \RuntimeException(
                    "Stok {$alat->nama_peralatan} tidak mencukupi. "
                    . "Tersedia: {$alat->stok_tersedia}, diminta: {$jumlahArr[$i]}."
                );
            }
            JadwalPeralatan::create([
                'id_penjadwalan'    => $jadwal->id_penjadwalan,
                'id_peralatan'      => $idPeralatan,
                'jumlah'            => $jumlahArr[$i],
                'status_pemasangan' => 'belum_dipasang',
            ]);
        }
    }
    private function kirimNotifKeOperator(Penjadwalan $jadwal, array $operatorIds): void
    {
        foreach ($operatorIds as $id) {
            $operator = User::find($id);
            if (!$operator?->nohp) continue;
            $pesan = $this->wa->templateJadwalBaru(
                $operator->nama_user,
                $jadwal->tanggal->format('d/m/Y'),
                $jadwal->waktu_mulai,
                $jadwal->waktu_selesai,
                $jadwal->judul_kegiatan,
                $jadwal->platform,
            );
            $this->wa->kirim($operator->nohp, $pesan);
        }
    }
}
