@extends('layouts.inventaris')
@section('title', 'Dashboard Inventaris')
@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Dashboard Inventaris</h1>
            <ul class="breadcrumb">
                <li><a href="#">Inventaris</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li class="active">Dashboard</li>
            </ul>
        </div>
    </div>

    {{-- Kartu Metrik --}}
    <ul class="box-info">
        <li>
            <i class='bx bxs-box' style="background:#4a90d9;"></i>
            <span class="text">
                <h3>{{ $totalPeralatan }}</h3>
                <p>Total Peralatan</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-check-circle' style="background:#28a745;"></i>
            <span class="text">
                <h3>{{ $totalTersedia }}</h3>
                <p>Tersedia</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-x-circle' style="background:#dc3545;"></i>
            <span class="text">
                <h3>{{ $totalHabis }}</h3>
                <p>Stok Habis</p>
            </span>
        </li>
        <li>
            <i class='bx bxs-error' style="background:#ffc107;"></i>
            <span class="text">
                <h3>{{ $totalRusak }}</h3>
                <p>Unit Rusak</p>
            </span>
        </li>
    </ul>

    <div class="table-data">
        {{-- Peralatan Kritis --}}
        <div class="order" style="flex: 1;">
            <div class="head">
                <h3>⚠️ Peralatan Stok Kritis</h3>
                <a href="{{ route('inventaris.peralatan.index', ['status' => 'kritis']) }}"
                   style="font-size:.82rem; color:#4a90d9;">Lihat semua</a>
            </div>
            @if($peralatanKritis->isEmpty())
                <p style="padding:1.5rem; color:#888; text-align:center;">
                    Semua stok aman 👍
                </p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Nama Peralatan</th>
                            <th>Lokasi</th>
                            <th>Stok Tersedia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peralatanKritis as $item)
                            <tr>
                                <td>{{ $item->nama_peralatan }}</td>
                                <td>{{ $item->lokasi_penyimpanan }}</td>
                                <td>
                                    <span class="badge" style="background:#fff3cd; color:#856404; padding:3px 10px; border-radius:20px; font-size:.78rem;">
                                        {{ $item->stok_tersedia }} unit
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Peminjaman Menunggu - placeholder Fase 3 --}}
        <div class="order" style="flex: 1;">
            <div class="head">
                <h3>📋 Peminjaman Menunggu</h3>
                <a href="{{ route('inventaris.peminjaman.index') }}"
                   style="font-size:.82rem; color:#4a90d9;">Lihat semua</a>
            </div>
            <p style="padding:1.5rem; color:#aaa; text-align:center; font-size:.85rem;">
                Fitur peminjaman akan aktif di Fase 3
            </p>
        </div>
    </div>
</main>
@endsection
