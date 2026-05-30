<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('peminjaman')) {
            return;
        }

        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->string('id_user', 10);        // harus varchar(10) sama seperti users.id_user
            $table->string('id_peralatan', 10);   // harus varchar(10) sama seperti peralatan.id_peralatan
            $table->unsignedInteger('jumlah');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->string('keperluan', 255);
            $table->enum('status', [
                'diajukan',
                'disetujui',
                'ditolak',
                'dikembalikan',
            ])->default('diajukan');
            $table->string('catatan_inventaris', 255)->nullable();
            $table->timestamps();

            // Foreign key — collation harus cocok dengan tabel referensi
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->foreign('id_peralatan')
                  ->references('id_peralatan')
                  ->on('peralatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};