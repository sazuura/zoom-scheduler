@extends('layouts.operator')
@section('title', 'Presensi Saya')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Presensi Saya</h1>
            </div>
        </div>
        {{-- Absen Hari Ini --}}
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Jadwal Hari Ini</h3>
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
                                $today = $now->toDateString();
                            @endphp
                            @foreach($jadwalHariIni as $jadwal)
                                @php
                                    $start = $jadwal->penjadwalan->startDateTime ?? null;
                                    $end = $jadwal->penjadwalan->endDateTime ?? null;
                                    $inTime = $start && $end ? $now->between($start, $end) : false;
                                    $hMinus1 = $start ? $start->copy()->subDay()->toDateString() : null;
                                    $canHadir = $start && $today == $start->toDateString() && $inTime;
                                    $canIzinSakit = $start && $today <= $hMinus1;
                                    $canSubmit = $jadwal->status === 'pending' && ($canHadir || $canIzinSakit);
                                @endphp
                                <tr>
                                    <td>{{ $jadwal->penjadwalan->judul_kegiatan }}</td>
                                    <td>{{ $start ? $start->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $start ? $start->format('H:i') : '-' }} - {{ $end ? $end->format('H:i') : '-' }}</td>

                                    @if($canSubmit)
                                        <td>
                                            <select name="status" id="status-{{ $jadwal->id_absensi }}"class="absensi-select" required>
                                                @if($canHadir)
                                                    <option value="hadir">Hadir</option>
                                                @endif
                                                @if($canIzinSakit)
                                                    <option value="izin">Izin</option>
                                                    <option value="sakit">Sakit</option>
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="keterangan" class="absensi-input" placeholder="Alasan jika izin / sakit">
                                        </td>
                                        <td>
                                            <form action="{{ route('operator.absensi.store') }}" method="POST" onsubmit="return confirmSubmit(this)">
                                                @csrf
                                                <input type="hidden" name="id_absensi" value="{{ $jadwal->id_absensi }}">
                                                <input type="hidden" name="status" id="hidden-status-{{ $jadwal->id_absensi }}">
                                                <input type="hidden" name="keterangan" id="hidden-keterangan-{{ $jadwal->id_absensi }}">
                                                <button type="submit" class="absensi-btn" onclick="
                                                    document.getElementById('hidden-status-{{ $jadwal->id_absensi }}').value = document.getElementById('status-{{ $jadwal->id_absensi }}').value;
                                                    document.getElementById('hidden-keterangan-{{ $jadwal->id_absensi }}').value = document.getElementById('keterangan-{{ $jadwal->id_absensi }}').value;
                                                ">Kirim</button>
                                            </form>
                                        </td>
                                    @else
                                        <td></td>
                                        @if ($jadwal->status != 'alpha')
                                            <td colspan="3"><span class="text-muted">Sudah absen ({{ ucfirst($jadwal->status) }})</span></td>
                                        @else
                                            <td colspan="3"><span class="text-muted">Belum absen ({{ ucfirst($jadwal->status) }})</span></td>
                                        @endif                                       
                                    @endif
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
                    <h3>Riwayat Presensi</h3>
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
            @if ($absensiSaya->hasPages())
                <div class="pagination-clean">
                    {{-- Previous --}}
                    @if ($absensiSaya->onFirstPage())
                        <span class="page-btn disabled">
                            <i class="bx bx-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $absensiSaya->previousPageUrl() }}" class="page-btn">
                            <i class="bx bx-chevron-left"></i>
                        </a>
                    @endif
                    {{-- Page Numbers --}}
                    @foreach ($absensiSaya->getUrlRange(1, $absensiSaya->lastPage()) as $page => $url)
                        @if ($page == $absensiSaya->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                    {{-- Next --}}
                    @if ($absensiSaya->hasMorePages())
                        <a href="{{ $absensiSaya->nextPageUrl() }}" class="page-btn">
                            <i class="bx bx-chevron-right"></i>
                        </a>
                    @else
                        <span class="page-btn disabled">
                            <i class="bx bx-chevron-right"></i>
                        </span>
                    @endif
                </div>
            @endif
    </main>
    <script>
        function confirmSubmit(form) {
            let status = form.querySelector('input[name="status"]').value;
            let keterangan = form.querySelector('input[name="keterangan"]').value;
            return confirm(`Apakah kamu yakin ingin mengisi absensi dengan status "${status}"?` + (keterangan ? `\nAlasan: ${keterangan}` : ''));
        }
    </script>
@endsection