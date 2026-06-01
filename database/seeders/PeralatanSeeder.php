<?php
namespace Database\Seeders;
use App\Models\Peralatan;
use Illuminate\Database\Seeder;

class PeralatanSeeder extends Seeder
{
    public function run(): void
    {
        $peralatan = [
            [
                'id_peralatan'  => 'PR-001',
                'kode_barang'   => 'GU/LAP/2023/001',
                'nama_peralatan'=> 'Laptop Zoom Host',
                'gedung'        => 'Gedung Utama',
                'lokasi_detail' => 'Ruang Server Lt.2, Rak A',
                'stok'          => 4, 'rusak' => 0, 'perbaikan' => 0,
            ],
            [
                'id_peralatan'  => 'PR-002',
                'kode_barang'   => 'GU/WEB/2023/001',
                'nama_peralatan'=> 'Webcam HD 1080p',
                'gedung'        => 'Gedung Utama',
                'lokasi_detail' => 'Ruang Server Lt.2, Rak A',
                'stok'          => 6, 'rusak' => 1, 'perbaikan' => 0,
            ],
            [
                'id_peralatan'  => 'PR-003',
                'kode_barang'   => 'GU/MIC/2022/001',
                'nama_peralatan'=> 'Mikrofon Omnidirectional',
                'gedung'        => 'Gedung Utama',
                'lokasi_detail' => 'Gudang Lt.1',
                'stok'          => 3, 'rusak' => 0, 'perbaikan' => 1,
            ],
            [
                'id_peralatan'  => 'PR-004',
                'kode_barang'   => 'GU/SPK/2022/001',
                'nama_peralatan'=> 'Speaker Active 15"',
                'gedung'        => 'Gedung Utama',
                'lokasi_detail' => 'Gudang Lt.1',
                'stok'          => 2, 'rusak' => 0, 'perbaikan' => 0,
            ],
            [
                'id_peralatan'  => 'PR-005',
                'kode_barang'   => 'GA/PRY/2021/001',
                'nama_peralatan'=> 'Proyektor HDMI 3500 Lumen',
                'gedung'        => 'Gedung A',
                'lokasi_detail' => 'Ruang Rapat Lt.1',
                'stok'          => 2, 'rusak' => 0, 'perbaikan' => 0,
            ],
            [
                'id_peralatan'  => 'PR-006',
                'kode_barang'   => 'GA/HDM/2023/001',
                'nama_peralatan'=> 'HDMI Splitter 1x4',
                'gedung'        => 'Gedung A',
                'lokasi_detail' => 'Lemari Elektronik Lt.1',
                'stok'          => 5, 'rusak' => 0, 'perbaikan' => 0,
            ],
            [
                'id_peralatan'  => 'PR-007',
                'kode_barang'   => 'GA/LYR/2021/001',
                'nama_peralatan'=> 'Layar Proyeksi 100"',
                'gedung'        => 'Gedung A',
                'lokasi_detail' => 'Ruang Rapat Lt.1',
                'stok'          => 2, 'rusak' => 0, 'perbaikan' => 1,
                'keterangan'    => 'Satu unit sedang perbaikan engsel.',
            ],
            [
                'id_peralatan'  => 'PR-008',
                'kode_barang'   => 'GB/TRP/2022/001',
                'nama_peralatan'=> 'Tripod Kamera Profesional',
                'gedung'        => 'Gedung B',
                'lokasi_detail' => 'Gudang Peralatan Lt.2',
                'stok'          => 3, 'rusak' => 0, 'perbaikan' => 0,
            ],
            [
                'id_peralatan'  => 'PR-009',
                'kode_barang'   => 'GB/UPS/2020/001',
                'nama_peralatan'=> 'UPS 1500VA',
                'gedung'        => 'Gedung B',
                'lokasi_detail' => 'Ruang Server Lt.1',
                'stok'          => 2, 'rusak' => 1, 'perbaikan' => 0,
                'keterangan'    => 'Satu unit rusak — baterai drop.',
            ],
            [
                'id_peralatan'  => 'PR-010',
                'kode_barang'   => null, 
                'nama_peralatan'=> 'Kabel HDMI 5m',
                'gedung'        => 'Gedung B',
                'lokasi_detail' => 'Gudang Peralatan Lt.2',
                'stok'          => 10, 'rusak' => 1, 'perbaikan' => 0,
            ],
        ];
        foreach ($peralatan as $data) {
            Peralatan::updateOrCreate(['id_peralatan' => $data['id_peralatan']], $data);
        }
        $this->command->info('PeralatanSeeder: ' . count($peralatan) . ' peralatan di 3 gedung berhasil dibuat.');
    }
}
