<?php
namespace Database\Seeders;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Illuminate\Database\Seeder;


class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'header' => [
                    'id_user'                 => 'US003',
                    'tanggal_pinjam'          => now()->subDays(20)->format('Y-m-d'),
                    'tanggal_kembali_rencana' => now()->subDays(17)->format('Y-m-d'),
                    'tanggal_kembali_aktual'  => now()->subDays(18)->format('Y-m-d'),
                    'keperluan'               => 'Rapat dinas luar kota bersama Kemendagri',
                    'status'                  => 'dikembalikan',
                    'catatan_inventaris'      => 'Dikembalikan tepat waktu, kondisi baik.',
                ],
                'items' => [
                    ['id_peralatan' => 'PR-001', 'jumlah' => 1], 
                    ['id_peralatan' => 'PR-002', 'jumlah' => 1], 
                ],
            ],
            [
                'header' => [
                    'id_user'                 => 'US004',
                    'tanggal_pinjam'          => now()->subDays(10)->format('Y-m-d'),
                    'tanggal_kembali_rencana' => now()->subDays(7)->format('Y-m-d'),
                    'tanggal_kembali_aktual'  => null,
                    'keperluan'               => 'Pelatihan di Balai Diklat Provinsi',
                    'status'                  => 'disetujui',
                    'catatan_inventaris'      => 'Disetujui. Harap kembalikan tepat waktu.',
                ],
                'items' => [
                    ['id_peralatan' => 'PR-005', 'jumlah' => 1], 
                    ['id_peralatan' => 'PR-006', 'jumlah' => 2], 
                    ['id_peralatan' => 'PR-008', 'jumlah' => 1], 
                ],
            ],
            [
                'header' => [
                    'id_user'                 => 'US005',
                    'tanggal_pinjam'          => now()->subDays(5)->format('Y-m-d'),
                    'tanggal_kembali_rencana' => now()->subDays(3)->format('Y-m-d'),
                    'tanggal_kembali_aktual'  => null,
                    'keperluan'               => 'Sosialisasi di Kecamatan Ngamprah',
                    'status'                  => 'ditolak',
                    'catatan_inventaris'      => 'Proyektor sedang dipakai kegiatan lain di tanggal yang sama.',
                ],
                'items' => [
                    ['id_peralatan' => 'PR-005', 'jumlah' => 2], 
                    ['id_peralatan' => 'PR-007', 'jumlah' => 1], 
                ],
            ],
            [
                'header' => [
                    'id_user'                 => 'US003',
                    'tanggal_pinjam'          => now()->addDays(2)->format('Y-m-d'),
                    'tanggal_kembali_rencana' => now()->addDays(4)->format('Y-m-d'),
                    'tanggal_kembali_aktual'  => null,
                    'keperluan'               => 'Workshop Literasi Digital di SDN Cimahi',
                    'status'                  => 'diajukan',
                    'catatan_inventaris'      => null,
                ],
                'items' => [
                    ['id_peralatan' => 'PR-001', 'jumlah' => 1], 
                    ['id_peralatan' => 'PR-005', 'jumlah' => 1], 
                    ['id_peralatan' => 'PR-010', 'jumlah' => 3], 
                ],
            ],
        ];

        foreach ($data as $entry) {
            $peminjaman = Peminjaman::create($entry['header']);
            foreach ($entry['items'] as $item) {
                PeminjamanItem::create([
                    'id_peminjaman' => $peminjaman->id_peminjaman,
                    'id_peralatan'  => $item['id_peralatan'],
                    'jumlah'        => $item['jumlah'],
                ]);
            }
        }
        $this->command->info('PeminjamanSeeder: ' . count($data) . ' pengajuan berhasil dibuat.');
    }
}
