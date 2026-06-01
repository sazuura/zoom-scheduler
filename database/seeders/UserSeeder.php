<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id_user'   => 'US001',
                'nama_user' => 'Admin Sazuura',
                'nohp'      => '081234560001',
                'email'     => 'admin@diskominfotik.go.id',
                'role'      => 'admin',
                'gedung'    => null,
            ],
            [
                'id_user'   => 'US002',
                'nama_user' => 'Inventaris Gedung Utama',
                'nohp'      => '081234560002',
                'email'     => 'inv.utama@diskominfotik.go.id',
                'role'      => 'inventaris',
                'gedung'    => 'Gedung Utama', 
            ],
            [
                'id_user'   => 'US006',
                'nama_user' => 'Inventaris Gedung A',
                'nohp'      => '081234560006',
                'email'     => 'inv.a@diskominfotik.go.id',
                'role'      => 'inventaris',
                'gedung'    => 'Gedung A',     
            ],
            [
                'id_user'   => 'US007',
                'nama_user' => 'Inventaris Gedung B',
                'nohp'      => '081234560007',
                'email'     => 'inv.b@diskominfotik.go.id',
                'role'      => 'inventaris',
                'gedung'    => 'Gedung B',     
            ],
            [
                'id_user'   => 'US003',
                'nama_user' => 'Budi Santoso',
                'nohp'      => '081234560003',
                'email'     => 'budi@diskominfotik.go.id',
                'role'      => 'operator',
                'gedung'    => null,
            ],
            [
                'id_user'   => 'US004',
                'nama_user' => 'Siti Rahayu',
                'nohp'      => '081234560004',
                'email'     => 'siti@diskominfotik.go.id',
                'role'      => 'operator',
                'gedung'    => null,
            ],
            [
                'id_user'   => 'US005',
                'nama_user' => 'Agus Permana',
                'nohp'      => '081234560005',
                'email'     => 'agus@diskominfotik.go.id',
                'role'      => 'operator',
                'gedung'    => null,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['id_user' => $data['id_user']],
                array_merge($data, ['password' => Hash::make('password')])
            );
        }
        $this->command->info('UserSeeder: ' . count($users) . ' user berhasil dibuat.');
        $this->command->warn('  → Password semua user: "password" — wajib diganti!');
    }
}
