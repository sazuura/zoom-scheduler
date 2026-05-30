@extends('layouts.operator')
@section('title', 'Peminjaman Peralatan')
@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Peminjaman Peralatan</h1>
        </div>
        <a href="{{ route('operator.peminjaman.create') }}" class="btn-download">
            <i class="bx bx-plus"></i>
            <span class="text">Ajukan Peminjaman</span>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter status --}}
    <form method="GET" action="{{ route('operator.peminjaman.index') }}" class="toolbar-form" style="margin-bottom:1.25rem;">
        <div class="filter-group">
            <select name="status">
                <option value="">Semua status</option>
                <option value="diajukan"     {{ request('status') == 'diajukan'     ? 'selected' : '' }}>Menunggu</option>
                <option value="disetujui"    {{ request('status') == 'disetujui'    ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak"      {{ request('status') == 'ditolak'      ? 'selected' : '' }}>Ditolak</option>
                <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
            <button type="submit" class="btn-apply">Terapkan</button>
            @if(request('status'))
                <a href="{{ route('operator.peminjaman.index') }}" class="btn-clear">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-data">
        <div class="order">
            <div class="head"><h3>Riwayat Peminjaman Saya</h3></div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Peralatan</th>
                        <th>Jumlah</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Keperluan</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $i => $p)
                        <tr>
                            <td>{{ $peminjaman->firstItem() + $i }}</td>
                            <td>{{ $p->peralatan->nama_peralatan ?? '-' }}</td>
                            <td>{{ $p->jumlah }} unit</td>
                            <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                            <td>{{ $p->keperluan }}</td>
                            <td>
                                <span class="badge {{ $p->badge['class'] }}">{{ $p->badge['label'] }}</span>
                            </td>
                            <td style="font-size:.8rem; color:#888;">
                                {{ $p->catatan_inventaris ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:2rem; color:#aaa;">
                                Belum ada riwayat peminjaman.
                                <a href="{{ route('operator.peminjaman.create') }}">Ajukan sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if($peminjaman->hasPages())
                <div class="pagination-clean">
                    @if($peminjaman->onFirstPage())
                        <span class="page-btn disabled"><i class="bx bx-chevron-left"></i></span>
                    @else
                        <a href="{{ $peminjaman->previousPageUrl() }}" class="page-btn"><i class="bx bx-chevron-left"></i></a>
                    @endif
                    @foreach($peminjaman->getUrlRange(1, $peminjaman->lastPage()) as $page => $url)
                        @if($page == $peminjaman->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($peminjaman->hasMorePages())
                        <a href="{{ $peminjaman->nextPageUrl() }}" class="page-btn"><i class="bx bx-chevron-right"></i></a>
                    @else
                        <span class="page-btn disabled"><i class="bx bx-chevron-right"></i></span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
