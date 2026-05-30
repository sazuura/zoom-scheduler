<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('peralatan')) {
            return;
        }

        Schema::create('peralatan', function (Blueprint $table) {
            $table->string('id_peralatan', 10)->primary();
            $table->string('nama_peralatan', 100);
            $table->string('lokasi_penyimpanan', 255);
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('rusak')->default(0);
            $table->unsignedInteger('perbaikan')->default(0);
            $table->string('keterangan', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peralatan');
    }
};
