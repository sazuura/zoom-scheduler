@extends('layouts.admin')
@section('title', 'Detail Jadwal')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Detail Jadwal</h1>
            </div>
            <a href="{{ route('admin.jadwal.index') }}" class="btn-download">
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
                        <td>{{ $jadwal->judul_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td><b>Tanggal</b></td>
                        <td>{{ $jadwal->tanggal->translatedFormat('l, d F Y') }}</td>
                    </tr>
                    <tr>
                        <td><b>Waktu</b></td>
                        <td>{{ $jadwal->waktu_mulai }} - {{ $jadwal->waktu_selesai }}</td>
                    </tr>
                    <tr>
                        <td><b>Platform</b></td>
                        <td>{{ $jadwal->platform }}</td>
                    </tr>
                    <tr>
                        <td><b>Keterangan</b></td>
                        <td>{{ $jadwal->keterangan }}</td>
                    </tr>
                    <tr>
                        <td><b>Operator</b></td>
                        <td>{{ $jadwal->user->nama_user }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </main>
@endsection