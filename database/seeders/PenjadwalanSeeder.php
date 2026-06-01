<?php
namespace Database\Seeders;
use App\Models\Absensi;
use App\Models\JadwalPeralatan;
use App\Models\Penjadwalan;
use Illuminate\Database\Seeder;

class PenjadwalanSeeder extends Seeder
{
    public function run(): void
    {
        $jadwal = [
            [
                'id'       => 'PJ001',
                'judul'    => 'Rapat Koordinasi Bulanan IT',
                'tanggal'  => now()->subDays(30)->format('Y-m-d'),
                'mulai'    => '09:00', 'selesai' => '11:00',
                'platform' => 'Online (Zoom)',
                'ket'      => 'Koordinasi rutin divisi IT',
                'peralatan'=> [
                    ['id' => 'PR-001', 'jumlah' => 1], 
                    ['id' => 'PR-005', 'jumlah' => 1], 
                ],
                'operator' => ['US003', 'US004'],
            ],
            [
                'id'       => 'PJ002',
                'judul'    => 'Sosialisasi Sistem Penjadwalan',
                'tanggal'  => now()->subDays(14)->format('Y-m-d'),
                'mulai'    => '13:00', 'selesai' => '15:00',
                'platform' => 'Offline (Aula Gedung Utama)',
                'ket'      => 'Sosialisasi ke seluruh staf operasional',
                'peralatan'=> [
                    ['id' => 'PR-001', 'jumlah' => 1], 
                    ['id' => 'PR-003', 'jumlah' => 2], 
                    ['id' => 'PR-004', 'jumlah' => 1], 
                ],
                'operator' => ['US004', 'US005'],
            ],
            [
                'id'       => 'PJ003',
                'judul'    => 'Workshop Digital Government',
                'tanggal'  => now()->subDays(7)->format('Y-m-d'),
                'mulai'    => '08:00', 'selesai' => '12:00',
                'platform' => 'Hybrid (Zoom + Offline)',
                'ket'      => 'Workshop bersama Kemenpan-RB',
                'peralatan'=> [
                    ['id' => 'PR-001', 'jumlah' => 2], 
                    ['id' => 'PR-005', 'jumlah' => 1], 
                    ['id' => 'PR-008', 'jumlah' => 2], 
                ],
                'operator' => ['US003', 'US005'],
            ],
            [
                'id'       => 'PJ004',
                'judul'    => 'Rapat Evaluasi Kinerja Q1',
                'tanggal'  => now()->addDays(3)->format('Y-m-d'),
                'mulai'    => '10:00', 'selesai' => '12:00',
                'platform' => 'Online (Google Meet)',
                'ket'      => 'Evaluasi kinerja triwulan pertama',
                'peralatan'=> [
                    ['id' => 'PR-001', 'jumlah' => 1], 
                    ['id' => 'PR-002', 'jumlah' => 1], 
                ],
                'operator' => ['US003'],
            ],
            [
                'id'       => 'PJ005',
                'judul'    => 'Pelatihan Keamanan Siber BSSN',
                'tanggal'  => now()->addDays(10)->format('Y-m-d'),
                'mulai'    => '09:00', 'selesai' => '16:00',
                'platform' => 'Online (Zoom)',
                'ket'      => 'Pelatihan wajib dari BSSN untuk seluruh staf IT',
                'peralatan'=> [
                    ['id' => 'PR-001', 'jumlah' => 1], 
                    ['id' => 'PR-006', 'jumlah' => 2], 
                ],
                'operator' => ['US004', 'US005'],
            ],
        ];

        foreach ($jadwal as $j) {
            $pj = Penjadwalan::updateOrCreate(
                ['id_penjadwalan' => $j['id']],
                [
                    'judul_kegiatan' => $j['judul'],
                    'tanggal'        => $j['tanggal'],
                    'waktu_mulai'    => $j['mulai'],
                    'waktu_selesai'  => $j['selesai'],
                    'platform'       => $j['platform'],
                    'keterangan'     => $j['ket'],
                    'id_pemateri'    => null,
                ]
            );

            foreach ($j['operator'] as $idUser) {
                $statusLalu = $this->pilihStatusRealistis();
                $sudahLewat = $j['tanggal'] < now()->format('Y-m-d');

                Absensi::updateOrCreate(
                    ['id_penjadwalan' => $j['id'], 'id_user' => $idUser],
                    [
                        'tanggal'   => $j['tanggal'],
                        'status'    => $sudahLewat ? $statusLalu : 'pending',
                        'validated' => $sudahLewat,
                    ]
                );
            }

            foreach ($j['peralatan'] as $item) {
                JadwalPeralatan::updateOrCreate(
                    ['id_penjadwalan' => $j['id'], 'id_peralatan' => $item['id']],
                    [
                        'jumlah'            => $item['jumlah'],
                        'status_pemasangan' => $j['tanggal'] < now()->format('Y-m-d')
                            ? 'sudah_dipasang'
                            : 'belum_dipasang',
                    ]
                );
            }
        }
        $this->command->info('PenjadwalanSeeder: ' . count($jadwal) . ' jadwal berhasil dibuat.');
    }

    private function pilihStatusRealistis(): string
    {
        return collect([
            'hadir', 'hadir', 'hadir', 'hadir',
            'izin_disetujui',
            'sakit_disetujui',
            'alpha',
        ])->random();
    }
}
