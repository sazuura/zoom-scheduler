@extends('layouts.admin')
@section('title', 'Detail Absensi')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Detail Absensi</h1>
            </div>
            <a href="{{ route('admin.absensi.index') }}" class="btn-download">
                <i class="bx bx-arrow-back"></i>
                <span class="text">Kembali</span>
            </a>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Informasi Jadwal</h3>
                </div>
                <table>
                    <tr>
                        <td><b>Judul</b></td>
                        <td>{{ $absensi->penjadwalan->judul_kegiatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><b>Tanggal</b></td>
                        <td>{{ $absensi->tanggal->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><b>Waktu</b></td>
                        <td>
                            {{ $absensi->penjadwalan->waktu_mulai }} -
                            {{ $absensi->penjadwalan->waktu_selesai }}
                        </td>
                    </tr>
                    <tr>
                        <td><b>Platform</b></td>
                        <td>{{ $absensi->penjadwalan->platform ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><b>Keterangan Jadwal</b></td>
                        <td>{{ $absensi->penjadwalan->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="order">
                <div class="head">
                    <h3>Operator</h3>
                </div>
                <table>
                    <tr>
                        <td><b>Nama Operator</b></td>
                        <td>{{ $absensi->user->nama_user }}</td>
                    </tr>
                    <tr>
                        <td><b>Status Absensi</b></td>
                        <td>
                            @switch($absensi->status)
                            @case('pending')
                                <span class="badge badge-warning">Pending</span>
                            @break
                            @case('hadir')
                                <span class="badge badge-active">Hadir</span>
                            @break
                            @case('izin')
                                <span class="badge badge-info">Izin</span>
                            @break
                            @case('sakit')
                                <span class="badge badge-purple">Sakit</span>
                            @break
                            @case('sakit_disetujui')
                                <span class="badge badge-purple">Sakit</span>
                            @break
                            @case('izin_disetujui')
                                <span class="badge badge-info">Izin</span>
                            @break
                            @case('alpha')
                                <span class="badge badge-danger">Alpha</span>
                            @break
                            @case('ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                            @break
                            @default
                                <span class="badge">Unknown</span>
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <td><b>Keterangan</b></td>
                        <td>{{ $absensi->keterangan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            <div class="order">
                <div class="head">
                    <h3>Dokumentasi</h3>
                </div>
                <div style="display:flex; gap:15px; flex-wrap:wrap">
                    @forelse($absensi->dokumentasi as $dok)
                        <div style="width:180px">
                            <img src="{{ asset('storage/' . $dok->foto) }}" style="width:100%; border-radius:8px;">
                            <p style="font-size:13px;margin-top:5px">
                                {{ $dok->keterangan }}
                            </p>
                        </div>
                    @empty
                        <p>Tidak ada dokumentasi</p>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
@endsection