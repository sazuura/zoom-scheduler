@extends('layouts.inventaris')
@section('title', 'Peminjaman')
@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Daftar Peminjaman</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('inventaris.dashboard') }}">Inventaris</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li class="active">Peminjaman</li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter --}}
    <form method="GET" action="{{ route('inventaris.peminjaman.index') }}" class="inv-toolbar">
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
                <a href="{{ route('inventaris.peminjaman.index') }}" class="btn-clear">Reset</a>
            @endif
        </div>
    </form>

    <div class="table-data">
        <div class="order">
            <div class="head"><h3>Semua Pengajuan Peminjaman</h3></div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Operator</th>
                        <th>Peralatan</th>
                        <th>Jml</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Keperluan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $i => $p)
                        <tr>
                            <td>{{ $peminjaman->firstItem() + $i }}</td>
                            <td>{{ $p->user->nama_user ?? '-' }}</td>
                            <td>{{ $p->peralatan->nama_peralatan ?? '-' }}</td>
                            <td>{{ $p->jumlah }}</td>
                            <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                            <td style="max-width:160px; font-size:.82rem;">{{ $p->keperluan }}</td>
                            <td>
                                <span class="badge {{ $p->badge['class'] }}">{{ $p->badge['label'] }}</span>
                            </td>
                            <td>
                                <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">

                                    {{-- Setujui --}}
                                    @if($p->isMenunggu())
                                        <form action="{{ route('inventaris.peminjaman.approve', $p->id_peminjaman) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-action success" title="Setujui"
                                                    onclick="return confirm('Setujui peminjaman ini? Stok akan dikurangi.')">
                                                <i class='bx bx-check'></i>
                                            </button>
                                        </form>

                                        {{-- Tolak (dengan modal sederhana) --}}
                                        <button type="button" class="btn-action danger" title="Tolak"
                                                onclick="toggleRejectForm({{ $p->id_peminjaman }})">
                                            <i class='bx bx-x'></i>
                                        </button>
                                    @endif

                                    {{-- Konfirmasi Kembali --}}
                                    @if($p->isDisetujui())
                                        <button type="button" class="btn-action edit" title="Konfirmasi Kembali"
                                                onclick="toggleKembaliForm({{ $p->id_peminjaman }})">
                                            <i class='bx bx-package'></i>
                                        </button>
                                    @endif

                                </div>

                                {{-- Form Tolak (inline, tersembunyi) --}}
                                @if($p->isMenunggu())
                                    <div id="reject-form-{{ $p->id_peminjaman }}" style="display:none; margin-top:.5rem;">
                                        <form action="{{ route('inventaris.peminjaman.reject', $p->id_peminjaman) }}" method="POST">
                                            @csrf
                                            <input type="text" name="catatan_inventaris" placeholder="Alasan penolakan..."
                                                   style="width:100%; padding:.35rem .6rem; border-radius:6px; border:1px solid #ddd; font-size:.8rem; margin-bottom:.35rem;">
                                            <button type="submit" class="btn-apply" style="font-size:.78rem; padding:.3rem .7rem;">
                                                Konfirmasi Tolak
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                {{-- Form Konfirmasi Kembali (inline, tersembunyi) --}}
                                @if($p->isDisetujui())
                                    <div id="kembali-form-{{ $p->id_peminjaman }}" style="display:none; margin-top:.5rem;">
                                        <form action="{{ route('inventaris.peminjaman.kembali', $p->id_peminjaman) }}" method="POST">
                                            @csrf
                                            <input type="date" name="tanggal_kembali_aktual"
                                                   value="{{ now()->format('Y-m-d') }}"
                                                   style="width:100%; padding:.35rem .6rem; border-radius:6px; border:1px solid #ddd; font-size:.8rem; margin-bottom:.35rem;">
                                            <input type="text" name="catatan_inventaris" placeholder="Catatan kondisi barang..."
                                                   style="width:100%; padding:.35rem .6rem; border-radius:6px; border:1px solid #ddd; font-size:.8rem; margin-bottom:.35rem;">
                                            <button type="submit" class="btn-apply" style="font-size:.78rem; padding:.3rem .7rem;"
                                                    onclick="return confirm('Konfirmasi barang sudah dikembalikan? Stok akan bertambah kembali.')">
                                                Konfirmasi Kembali
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center; padding:2rem; color:#aaa;">
                                Belum ada pengajuan peminjaman.
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

@section('scripts')
<script>
    function toggleRejectForm(id) {
        const el = document.getElementById('reject-form-' + id);
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
    function toggleKembaliForm(id) {
        const el = document.getElementById('kembali-form-' + id);
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endsection
