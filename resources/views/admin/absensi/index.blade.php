@extends('layouts.admin')
@section('title', 'Validasi Absensi')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Validasi Absensi</h1>
            </div>
        </div>
        <div class="toolbar">
            <form method="GET" action="{{ route('admin.absensi.index') }}" class="toolbar-form">
                <div class="search-box">
                    <i class="bx bx-search"></i>
                    <input type="text" name="search" placeholder="Cari kegiatan/operator..." value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <select name="status">
                        <option value="">Semua status</option>
                        <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>
                            Hadir
                        </option>
                        <option value="tidak_hadir" {{ request('status') == 'tidak_hadir' ? 'selected' : '' }}>
                            Tidak Hadir
                        </option>
                        <option value="perlu_disetujui" {{ request('status') == 'perlu_disetujui' ? 'selected' : '' }}>
                            Perlu Disetujui
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                    </select>
                    <button type="submit" class="btn-apply">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.absensi.index') }}" class="btn-clear">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Data Absensi</h3>
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
                                <td>{{ $absen->tanggal?->format('d/m/Y') ?? '-' }}
                                    @if($absen->penjadwalan?->waktu_mulai && $absen->penjadwalan?->waktu_selesai)
                                        ({{ \Carbon\Carbon::parse($absen->penjadwalan->waktu_mulai)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($absen->penjadwalan->waktu_selesai)->format('H:i') }})
                                    @endif
                                </td>
                                <td>{{ $absen->user->nama_user ?? '-' }}</td>
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
                                <td>
                                    <div style="display:flex; gap:6px; align-items:center;">
                                        {{-- VALIDASI IZIN / SAKIT --}}
                                        @if($absen->status == 'izin' || $absen->status == 'sakit')
                                            <form action="{{ route('admin.absensi.updateStatus', [$absen->id_absensi, $absen->status.'_disetujui']) }}" method="POST">
                                                @csrf
                                                <button class="btn-action success" title="Setujui">
                                                    <i class='bx bx-check'></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.absensi.updateStatus', [$absen->id_absensi,'ditolak']) }}" method="POST">
                                                @csrf
                                                <button class="btn-action danger" title="Tolak">
                                                    <i class='bx bx-x'></i>
                                                </button>
                                            </form>
                                        @endif
                                        {{-- DETAIL HADIR --}}
                                        @if($absen->status == 'hadir')
                                            <a href="{{ route('admin.absensi.show',$absen->id_absensi) }}" class="btn-action info" title="Detail">
                                                <i class='bx bx-show'></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($absensi->hasPages())
                    <div class="pagination-clean">
                        {{-- Previous --}}
                        @if ($absensi->onFirstPage())
                            <span class="page-btn disabled">
                                <i class="bx bx-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $absensi->previousPageUrl() }}" class="page-btn">
                                <i class="bx bx-chevron-left"></i>
                            </a>
                        @endif
                        {{-- Page Numbers --}}
                        @foreach ($absensi->getUrlRange(1, $absensi->lastPage()) as $page => $url)
                            @if ($page == $absensi->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach
                        {{-- Next --}}
                        @if ($absensi->hasMorePages())
                            <a href="{{ $absensi->nextPageUrl() }}" class="page-btn">
                                <i class="bx bx-chevron-right"></i>
                            </a>
                        @else
                            <span class="page-btn disabled">
                                <i class="bx bx-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection