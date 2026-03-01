@extends('layouts.operator')

@section('title', 'Jadwal Saya')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Jadwal Operator</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Daftar Jadwal</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Jadwal</th>
                        <th>Judul Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Platform</th>
                        <th>Operator</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $j)
                        <tr>
                            <td>{{ $j->id_penjadwalan }}</td>
                            <td>{{ $j->judul_kegiatan }}</td>
                            <td>{{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('l, d F Y') }}</td>
                            <td>{{ $j->waktu_mulai }} - {{ $j->waktu_selesai }}</td>
                            <td>{{ $j->platform }}</td>
                            <td>{{ $j->user->nama_user ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">Belum ada jadwal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
