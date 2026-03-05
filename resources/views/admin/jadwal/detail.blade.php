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
            {{-- Informasi Jadwal --}}
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
                </table>
            </div>

            {{-- Operator --}}
            <div class="order">
                <div class="head">
                    <h3>Operator</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Operator</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal->absensi as $index => $a)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $a->user->nama_user }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align:center">Tidak ada operator</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Peralatan --}}
            <div class="order">
                <div class="head">
                    <h3>Peralatan Digunakan</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peralatan</th>
                            <th>Jumlah</th>
                            <th>Status Pemasangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal->jadwalPeralatan as $alat)
                            <tr>
                                <td>{{ $alat->peralatan->nama_peralatan }}</td>
                                <td>{{ $alat->jumlah }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">Tidak ada peralatan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection