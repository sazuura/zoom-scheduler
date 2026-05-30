@extends('layouts.admin')
@section('title', 'Validasi Presensi')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Validasi Presensi</h1>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="toolbar">
            <form method="GET" action="{{ route('admin.absensi.index') }}" class="toolbar-form">
                <div class="search-box">
                    <i class="bx bx-search"></i>
                    <input type="text" name="search" placeholder="Cari kegiatan/operator..." value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <select name="status">
                        <option value="">Semua status</option>
                        <option value="hadir"           {{ request('status') == 'hadir'           ? 'selected' : '' }}>Hadir</option>
                        <option value="tidak_hadir"     {{ request('status') == 'tidak_hadir'     ? 'selected' : '' }}>Tidak Hadir</option>
                        <option value="perlu_disetujui" {{ request('status') == 'perlu_disetujui' ? 'selected' : '' }}>Perlu Disetujui</option>
                        <option value="pending"         {{ request('status') == 'pending'         ? 'selected' : '' }}>Pending</option>
                    </select>
                    <button type="submit" class="btn-apply">Terapkan</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.absensi.index') }}" class="btn-clear">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Data Presensi</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
                            <th>Jadwal</th>
                            <th>Operator</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $index => $absen)
                            <tr>
                                <td>{{ $absensi->firstItem() + $index }}</td>
                                <td>{{ $absen->penjadwalan->judul_kegiatan ?? '-' }}</td>
                                <td>
                                    {{ $absen->tanggal?->format('d/m/Y') ?? '-' }}
                                    @if($absen->penjadwalan?->waktu_mulai && $absen->penjadwalan?->waktu_selesai)
                                        ({{ \Carbon\Carbon::parse($absen->penjadwalan->waktu_mulai)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($absen->penjadwalan->waktu_selesai)->format('H:i') }})
                                    @endif
                                </td>
                                <td>{{ $absen->user->nama_user ?? '-' }}</td>
                                <td>
                                    @php
                                        $badgeMap = [
                                            'pending'         => ['class' => 'badge-warning', 'label' => 'Pending'],
                                            'hadir'           => ['class' => 'badge-active',  'label' => 'Hadir'],
                                            'izin'            => ['class' => 'badge-info',    'label' => 'Izin (Menunggu)'],
                                            'izin_disetujui'  => ['class' => 'badge-info',    'label' => 'Izin'],
                                            'sakit'           => ['class' => 'badge-purple',  'label' => 'Sakit (Menunggu)'],
                                            'sakit_disetujui' => ['class' => 'badge-purple',  'label' => 'Sakit'],
                                            'alpha'           => ['class' => 'badge-danger',  'label' => 'Alpha'],
                                            'ditolak'         => ['class' => 'badge-danger',  'label' => 'Ditolak'],
                                        ];
                                        $badge = $badgeMap[$absen->status] ?? ['class' => '', 'label' => $absen->status];
                                    @endphp
                                    <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                                </td>
                                <td>{{ $absen->keterangan ?? '-' }}</td>
                                <td>
                                    <div style="display:flex; gap:6px; align-items:center;">

                                        {{-- PERBAIKAN KEAMANAN:
                                             Status sekarang dikirim lewat hidden input di POST body,
                                             BUKAN lagi lewat URL parameter.
                                             Ini mencegah manipulasi status dari luar. --}}

                                        @if(in_array($absen->status, ['izin', 'sakit']))
                                            {{-- Tombol Setujui --}}
                                            <form action="{{ route('admin.absensi.updateStatus', $absen->id_absensi) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="{{ $absen->status }}_disetujui">
                                                <button type="submit" class="btn-action success" title="Setujui">
                                                    <i class='bx bx-check'></i>
                                                </button>
                                            </form>
                                            {{-- Tombol Tolak --}}
                                            <form action="{{ route('admin.absensi.updateStatus', $absen->id_absensi) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="ditolak">
                                                <button type="submit" class="btn-action danger" title="Tolak">
                                                    <i class='bx bx-x'></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Detail jika sudah hadir --}}
                                        @if($absen->status == 'hadir')
                                            <a href="{{ route('admin.absensi.show', $absen->id_absensi) }}"
                                               class="btn-action info" title="Lihat Detail">
                                                <i class='bx bx-show'></i>
                                            </a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding: 2rem; color: #999;">
                                    Belum ada data presensi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($absensi->hasPages())
                    <div class="pagination-clean">
                        @if($absensi->onFirstPage())
                            <span class="page-btn disabled"><i class="bx bx-chevron-left"></i></span>
                        @else
                            <a href="{{ $absensi->previousPageUrl() }}" class="page-btn"><i class="bx bx-chevron-left"></i></a>
                        @endif

                        @foreach($absensi->getUrlRange(1, $absensi->lastPage()) as $page => $url)
                            @if($page == $absensi->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($absensi->hasMorePages())
                            <a href="{{ $absensi->nextPageUrl() }}" class="page-btn"><i class="bx bx-chevron-right"></i></a>
                        @else
                            <span class="page-btn disabled"><i class="bx bx-chevron-right"></i></span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection
