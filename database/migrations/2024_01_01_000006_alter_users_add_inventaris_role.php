<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan nilai 'inventaris' ke kolom role di tabel users.
     *
     * Kenapa pakai DB::statement langsung?
     * Laravel Schema Builder tidak punya cara native untuk ubah nilai enum
     * yang sudah ada tanpa membuang dan membuat ulang kolom (yang berisiko
     * kehilangan data). Cara ini lebih aman: ALTER TABLE langsung.
     */
    public function up(): void
    {
        // Cek dulu apakah kolom role sudah punya nilai 'inventaris'
        // Kalau sudah ada, skip — migration ini aman dijalankan berkali-kali
        $column = DB::select("SHOW COLUMNS FROM `users` LIKE 'role'");

        if (empty($column)) {
            return; // kolom role belum ada, skip
        }

        $type = $column[0]->Type ?? '';

        if (str_contains($type, 'inventaris')) {
            return; // sudah ada nilai inventaris, tidak perlu alter
        }

        DB::statement("
            ALTER TABLE `users`
            MODIFY COLUMN `role`
            ENUM('admin', 'operator', 'inventaris') NOT NULL
        ");
    }

    public function down(): void
    {
        // Kembalikan ke enum tanpa inventaris
        // Pastikan tidak ada user dengan role inventaris sebelum rollback
        DB::statement("
            ALTER TABLE `users`
            MODIFY COLUMN `role`
            ENUM('admin', 'operator') NOT NULL
        ");
    }
};
