@extends('layouts.operator')
@section('title', 'Absensi Saya')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Absensi Saya</h1>
            </div>
        </div>
        {{-- Absen Hari Ini --}}
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Absen Hari Ini</h3>
                </div>
                @if($jadwalHariIni->isEmpty())
                    <div style="background:#fff; padding:12px; border-radius:8px;">
                        Tidak ada jadwal untuk hari ini.
                    </div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Judul Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $now = \Carbon\Carbon::now('Asia/Jakarta');
                            @endphp
                            @foreach($jadwalHariIni as $jadwal)
                                @php
                                    $start = $jadwal->penjadwalan->startDateTime ?? null;
                                    $end = $jadwal->penjadwalan->endDateTime ?? null;
                                    $inTime = $start && $end ? $now->between($start, $end) : false;
                                @endphp
                                <tr>
                                    <form action="{{ route('operator.absensi.store') }}" method="POST">
                                        @csrf
                                        <td>
                                            {{ $jadwal->penjadwalan->judul_kegiatan }}
                                            <input type="hidden" name="id_penjadwalan" value="{{ $jadwal->id_penjadwalan }}">
                                        </td>
                                        <td>
                                            {{ $start ? $start->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            {{ $start ? $start->format('H:i') : '-' }}
                                            -
                                            {{ $end ? $end->format('H:i') : '-' }}
                                        </td>
                                        <td>
                                            <select name="status" class="absensi-select" required>
                                                <option value="hadir">Hadir</option>
                                                <option value="izin">Izin</option>
                                                <option value="sakit">Sakit</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="keterangan" class="absensi-input"
                                                placeholder="Alasan jika izin / sakit">
                                        </td>
                                        <td>
                                            <button type="submit" class="absensi-btn" {{ !$inTime ? 'disabled' : '' }}>
                                                Kirim
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

        {{-- Riwayat Absensi --}}
        <div class="table-data" style="margin-top:20px;">
            <div class="order">
                <div class="head">
                    <h3>Riwayat Absensi</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jadwal</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensiSaya as $absen)
                            @php
                                $start = $absen->penjadwalan ? $absen->penjadwalan->startDateTime : null;
                                $end = $absen->penjadwalan ? $absen->penjadwalan->endDateTime : null;
                            @endphp
                            <tr>
                                <td>{{ $absen->tanggal ? $absen->tanggal->format('d/m/Y') : '-' }}</td>
                                <td>{{ $absen->penjadwalan->judul_kegiatan ?? '-' }}</td>
                                <td>
                                    @if($start && $end)
                                        {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @switch($absen->status)
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
                                <td>{{ $absen->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection