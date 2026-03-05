@extends('layouts.admin')
@section('title', 'Data Jadwal Rapat')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Data Jadwal Rapat</h1>
            </div>
            <a href="{{ route('admin.jadwal.create') }}" class="btn-download">
                <i class="bx bx-plus"></i>
                <span class="text">Tambah Jadwal</span>
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin:10px 0; padding:10px; border-radius:6px;">
                {{ session('success') }}
            </div>
        @endif
        <div class="toolbar">
            <form method="GET" action="{{ route('admin.jadwal.index') }}" class="toolbar-form">

                <div class="search-box">
                    <i class="bx bx-search"></i>
                    <input type="text" name="search" placeholder="Cari jadwal..." value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <select name="platform">
                        <option value="">Semua platform</option>
                        <option value="online" {{ request('platform') == 'online' ? 'selected' : '' }}>
                            Online
                        </option>
                        <option value="offline" {{ request('platform') == 'offline' ? 'selected' : '' }}>
                            Offline
                        </option>
                    </select>

                    <button type="submit" class="btn-apply">
                        Terapkan
                    </button>

                    @if(request()->hasAny(['search', 'platform']))
                        <a href="{{ route('admin.jadwal.index') }}" class="btn-clear">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>List Jadwal</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Platform</th>
                            <th>Operator</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwal as $index => $j)
                            <tr>
                                <td>{{ $jadwal->firstItem() + $index }}</td>
                                <td>{{ $j->judul_kegiatan }}</td>
                                <td>{{ $j->tanggal->translatedFormat('l, d F Y') }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }}
                                </td>
                                <td>{{ str_contains($j->platform, 'Online') ? 'Online' : 'Offline' }}</td>
                                <td>@foreach($j->absensi as $a)
                                    {{ $a->user->nama_user }}@if(!$loop->last), @endif
                                @endforeach
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @php
                                            $jadwalTime = \Carbon\Carbon::parse($j->tanggal->format('Y-m-d') . ' ' . $j->waktu_selesai);
                                        @endphp
                                        @if($jadwalTime->isPast())
                                            {{-- DETAIL --}}
                                            <a href="{{ route('admin.jadwal.show', $j->id_penjadwalan) }}" class="btn-action info">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        @else
                                            {{-- EDIT --}}
                                            <a href="{{ route('admin.jadwal.edit', $j->id_penjadwalan) }}" class="btn-action edit">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            {{-- DELETE --}}
                                            <form action="{{ route('admin.jadwal.destroy', $j->id_penjadwalan) }}" method="POST"
                                                style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action delete"
                                                    onclick="return confirm('Yakin ingin menghapus jadwal ini?')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:15px;">Belum ada jadwal</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($jadwal->hasPages())
                    <div class="pagination-clean">

                        {{-- Previous --}}
                        @if ($jadwal->onFirstPage())
                            <span class="page-btn disabled">
                                <i class="bx bx-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $jadwal->previousPageUrl() }}" class="page-btn">
                                <i class="bx bx-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($jadwal->getUrlRange(1, $jadwal->lastPage()) as $page => $url)
                            @if ($page == $jadwal->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($jadwal->hasMorePages())
                            <a href="{{ $jadwal->nextPageUrl() }}" class="page-btn">
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