<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jadwal_peralatan')) {
            return;
        }

        Schema::create('jadwal_peralatan', function (Blueprint $table) {
            $table->id('id_jadwal_alat');
            $table->string('id_penjadwalan', 10);
            $table->string('id_peralatan', 10);
            $table->unsignedInteger('jumlah')->default(1);
            $table->enum('status_pemasangan', ['belum_dipasang', 'sudah_dipasang'])->default('belum_dipasang');

            $table->foreign('id_penjadwalan')->references('id_penjadwalan')->on('penjadwalan')->cascadeOnDelete();
            $table->foreign('id_peralatan')->references('id_peralatan')->on('peralatan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_peralatan');
    }
};
