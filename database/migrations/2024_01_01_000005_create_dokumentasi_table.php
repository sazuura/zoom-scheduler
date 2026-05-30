<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('dokumentasi')) {
            return;
        }

        Schema::create('dokumentasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_absensi');
            $table->string('file_path', 255);
            $table->timestamps();

            $table->foreign('id_absensi')->references('id_absensi')->on('absensi')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasi');
    }
};
