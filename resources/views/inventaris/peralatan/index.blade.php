@extends('layouts.inventaris')
@section('title', 'Peralatan')
@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Daftar Peralatan</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('inventaris.dashboard') }}">Inventaris</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li class="active">Peralatan</li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Toolbar Search & Filter --}}
    <form method="GET" action="{{ route('inventaris.peralatan.index') }}" class="inv-toolbar">
        <div class="search-box">
            <i class="bx bx-search"></i>
            <input type="text" name="search" placeholder="Cari nama / lokasi..." value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <select name="status">
                <option value="">Semua</option>
                <option value="tersedia"       {{ request('status') == 'tersedia'       ? 'selected' : '' }}>Tersedia</option>
                <option value="tidak_tersedia" {{ request('status') == 'tidak_tersedia' ? 'selected' : '' }}>Habis</option>
                <option value="kritis"         {{ request('status') == 'kritis'         ? 'selected' : '' }}>⚠️ Kritis</option>
            </select>
            <button type="submit" class="btn-apply">Terapkan</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('inventaris.peralatan.index') }}" class="btn-clear">Reset</a>
            @endif
        </div>
    </form>

    {{-- Summary bar --}}
    <div style="display:flex; gap:1rem; margin-bottom:1.25rem; flex-wrap:wrap;">
        <span style="font-size:.83rem; color:#666;">
            Menampilkan <strong>{{ $peralatan->count() }}</strong> peralatan
        </span>
    </div>

    {{-- Grid Marketplace --}}
    @if($peralatan->isEmpty())
        <div style="text-align:center; padding:3rem; color:#aaa;">
            <i class='bx bx-box' style="font-size:3rem;"></i>
            <p style="margin-top:.5rem;">Tidak ada peralatan ditemukan.</p>
        </div>
    @else
        <div class="peralatan-grid">
            @foreach($peralatan as $item)
                @php
                    $stok = $item->stok_tersedia;
                    if ($stok <= 0)       { $badgeClass = 'habis';    $badgeLabel = 'Stok Habis'; }
                    elseif ($stok <= 2)   { $badgeClass = 'minim';    $badgeLabel = $stok.' unit — Kritis'; }
                    else                  { $badgeClass = 'tersedia';  $badgeLabel = $stok.' tersedia'; }
                @endphp
                <div class="peralatan-card">
                    {{-- Foto atau ikon placeholder --}}
                    <div class="card-img">
                        @if(!empty($item->foto))
                            <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->nama_peralatan }}">
                        @else
                            <i class='bx bx-camera-off' style="font-size:2.5rem; color:#ccc;"></i>
                        @endif
                    </div>

                    <div class="card-body">
                        <p class="card-title">{{ $item->nama_peralatan }}</p>
                        <p class="card-location">
                            <i class='bx bxs-map-pin' style="font-size:.9rem;"></i>
                            {{ $item->lokasi_penyimpanan }}
                        </p>

                        <div class="card-stok">
                            <div style="font-size:.78rem; color:#999;">
                                Total: {{ $item->stok }} &nbsp;·&nbsp;
                                Rusak: {{ $item->rusak ?? 0 }}
                            </div>
                            <span class="stok-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</main>
@endsection
