@extends('layouts.admin')
@section('title', 'Data Peralatan')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Data Peralatan</h1>
            </div>
            <a href="{{ route('admin.peralatan.create') }}" class="btn-download">
                <i class="bx bx-plus"></i>
                <span class="text">Tambah Peralatan</span>
            </a>
        </div>
        @if(session('success'))
            <div class="alert alert-success" style="margin:10px 0; padding:10px; border-radius:6px;">
                {{ session('success') }}
            </div>
        @endif
        <div class="toolbar">
            <form method="GET" action="{{ route('admin.peralatan.index') }}" class="toolbar-form">
                <div class="search-box">
                    <i class="bx bx-search"></i>
                    <input type="text" name="search" placeholder="Cari barang..." value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <select name="status">
                        <option value="">Semua status</option>
                        <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>
                            Tersedia
                        </option>
                        <option value="tidak-tersedia" {{ request('status') == 'tidak-tersedia' ? 'selected' : '' }}>
                            Tidak Tersedia
                        </option>
                    </select>
                    <button type="submit" class="btn-apply">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.peralatan.index') }}" class="btn-clear">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>List Peralatan</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peralatan</th>
                            <th>Lokasi</th>
                            <th>Stok</th>
                            <th>Ketersediaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peralatan as $index => $item)
                            <tr>
                                <td>{{ $peralatan->firstItem() + $index}}</td>
                                <td>{{ $item->nama_peralatan }}</td>
                                <td>{{ $item->lokasi_penyimpanan }}</td>
                                <td>{{ $item->stok_tersedia }}</td>
                                <td>
                                    @if($item->status === 'Tersedia')
                                        <span class="badge badge-active">
                                            <i class="bx bx-check-circle"></i> Tersedia
                                        </span>
                                    @else
                                        <span class="badge badge-inactive">
                                            <i class="bx bx-x-circle"></i> Tidak Tersedia
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.peralatan.edit', $item->id_peralatan) }}"
                                            class="btn-action edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        {{-- Tombol Hapus --}}
                                        @if($item->dipakai > 0)
                                            <button class="btn-action delete disabled-btn" disabled
                                                title="Barang sedang digunakan dalam jadwal">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('admin.peralatan.destroy', $item->id_peralatan) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action delete"
                                                    onclick="return confirm('Yakin hapus peralatan ini?')">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:15px;">Belum ada peralatan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($peralatan->hasPages())
                    <div class="pagination-clean">

                        {{-- Previous --}}
                        @if ($peralatan->onFirstPage())
                            <span class="page-btn disabled">
                                <i class="bx bx-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $peralatan->previousPageUrl() }}" class="page-btn">
                                <i class="bx bx-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($peralatan->getUrlRange(1, $peralatan->lastPage()) as $page => $url)
                            @if ($page == $peralatan->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($peralatan->hasMorePages())
                            <a href="{{ $peralatan->nextPageUrl() }}" class="page-btn">
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