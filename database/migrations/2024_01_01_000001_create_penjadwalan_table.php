<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('penjadwalan')) {
            return;
        }

        Schema::create('penjadwalan', function (Blueprint $table) {
            $table->string('id_penjadwalan', 10)->primary();
            $table->string('judul_kegiatan', 150);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('platform', 50);
            $table->string('keterangan', 150)->nullable();
            $table->string('id_pemateri', 10)->nullable();
            $table->foreign('id_pemateri')->references('id_user')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjadwalan');
    }
};
