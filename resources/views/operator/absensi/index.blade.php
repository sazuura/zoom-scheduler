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
                <div style="background:#fff; padding:12px; border-radius:8px;">Tidak ada jadwal untuk hari ini.</div>
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
                        @php $now = \Carbon\Carbon::now('Asia/Jakarta'); @endphp

                        @foreach($jadwalHariIni as $jadwal)
                            @php
                                $start = $jadwal->startDateTime; // Carbon
                                $end   = $jadwal->endDateTime;
                                $existing = $absensiSaya->firstWhere('id_penjadwalan', $jadwal->id_penjadwalan);
                                $inTime = $start && $end ? $now->between($start, $end) : false;
                            @endphp

                            <tr>
                                <td>{{ $jadwal->judul_kegiatan ?? '-' }}</td>
                                <td>{{ $start ? $start->format('d/m/Y') : '-' }}</td>
                                <td>
                                    {{ $start ? $start->format('H:i') : '-' }} - {{ $end ? $end->format('H:i') : '-' }}
                                </td>

                                <td>
                                    @if(!$existing)
                                        <form action="{{ route('operator.absensi.store') }}" method="POST" style="display:flex; gap:8px; align-items:center;">
                                            @csrf
                                            <input type="hidden" name="id_penjadwalan" value="{{ $jadwal->id_penjadwalan }}">
                                            <select name="status" class="form-select form-select-sm" required>
                                                <option value="hadir">Hadir</option>
                                                <option value="izin">Izin</option>
                                                <option value="sakit">Sakit</option>
                                                <option value="tidak_hadir">Tidak Hadir</option>
                                            </select>
                                    @else
                                        {{ ucfirst($existing->status) }}
                                    @endif
                                </td>

                                <td>
                                    @if(!$existing)
                                        <input type="text" name="keterangan" class="form-control form-control-sm" placeholder="Opsional">
                                    @else
                                        {{ $existing->keterangan ?? '-' }}
                                    @endif
                                </td>

                                <td>
                                    @if(!$existing)
                                        <button type="submit"
                                            class="btn-download"
                                            {{ !$inTime ? 'disabled' : '' }}>
                                            <span class="text">Absen</span>
                                        </button>
                                        </form>
                                    @else
                                        <form action="{{ route('operator.absensi.cancel', $existing->id_absensi) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action delete" onclick="return confirm('Batalkan absensi ini?')">Batal</button>
                                        </form>
                                    @endif
                                </td>
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
                        <th>Validasi</th>
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
                            <td>{{ ucfirst($absen->status) }}</td>
                            <td>{{ $absen->keterangan ?? '-' }}</td>
                            <td>
                                @if($absen->validated)
                                    <span style="color:green;">✔ Valid</span>
                                @else
                                    <span style="color:orange;">Menunggu</span>
                                @endif
                            </td>
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
