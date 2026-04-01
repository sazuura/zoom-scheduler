@extends('layouts.admin')
@section('title', 'Laporan Penjadwalan')

@section('content')
<main>

    <!-- HEADER -->
    <div class="head-title">
        <div class="left">
            <h1>Laporan Penjadwalan</h1>
        </div>
    </div>

    <!-- TOOLBAR -->
    <div class="toolbar">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="toolbar-form">
            {{-- LEFT SIDE --}}
            <div class="search-box" style="display:flex; gap:10px; max-width:none;">
                <div style=" flex-direction:column;">
                    <label style="font-size:12px; font-weight:600;">Dari Tanggal</label>
                    <input type="date" name="start"value="{{ request('start') }}"class="form-control">
                </div>
                <div style=" flex-direction:column;">
                    <label style="font-size:12px; font-weight:600;">Sampai Tanggal</label>
                    <input type="date" name="end" value="{{ request('end') }}"class="form-control">
                </div>
            </div>
            {{-- RIGHT SIDE --}}
            <div class="filter-group" style="align-items:flex-end;">
                <div style="display:flex; flex-direction:column;">
                    <label style="font-size:12px; font-weight:600;">Operator</label>
                    <select name="operator">
                        <option value="">Semua Operator</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->id_user }}"
                                {{ request('operator') == $op->id_user ? 'selected' : '' }}>
                                {{ $op->nama_user }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-apply">
                    Terapkan
                </button>
                @if(request()->hasAny(['start','end','operator']))
                    <a href="{{ route('admin.laporan.index') }}" class="btn-clear">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- EXPORT BUTTON -->
    <div style="display:flex; justify-content:flex-end; margin-bottom:15px; gap:10px;">
        <a href="{{ route('admin.laporan.exportPdf', request()->all()) }}"
           style="padding:8px 18px; background:#DB504A; color:#fff; border-radius:6px; text-decoration:none;">
            <i class='bx bxs-file-pdf'></i> Export PDF
        </a>

        <a href="{{ route('admin.laporan.exportExcel', request()->all()) }}"
           style="padding:8px 18px; background:#3C91E6; color:#fff; border-radius:6px; text-decoration:none;">
            <i class='bx bxs-file-export'></i> Export Excel
        </a>
    </div>

    <!-- TABLE + TAB -->
    <div class="table-data">
        <div class="order bookmark-wrapper">

            <!-- TAB -->
            <div class="bookmark-tabs">
                <button class="tab-btn active" data-tab="jadwal">Jadwal</button>
                <button class="tab-btn" data-tab="peralatan">Peralatan</button>
            </div>

            <!-- TITLE -->
            <div class="head">
                <h3 id="tab-title">Rekap Jadwal & Presensi</h3>
            </div>

            <!-- TAB JADWAL -->
            <div class="tab-content active" id="tab-jadwal">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Judul Kegiatan</th>
                            <th>Operator</th>
                            <th>Waktu</th>
                            <th>Status Presensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $j)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($j->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $j->penjadwalan->judul_kegiatan }}</td>
                            <td>{{ $j->user->nama_user ?? '-' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($j->penjadwalan->waktu_mulai)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($j->penjadwalan->waktu_selesai)->format('H:i') }}
                            </td>
                            <td>
                                @switch($j->status)
                                    @case('hadir')
                                        <span class="badge badge-active">Hadir</span>
                                    @break
                                    @case('izin')
                                        <span class="badge badge-info">Izin</span>
                                    @break
                                    @case('sakit')
                                        <span class="badge badge-purple">Sakit</span>
                                    @break
                                    @case('alpha')
                                        <span class="badge badge-danger">Alpha</span>
                                    @break
                                    @default
                                        <span class="badge badge-warning">Pending</span>
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TAB PERALATAN -->
            <div class="tab-content" id="tab-peralatan" style="display:none;">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Kegiatan</th>
                            <th>Peralatan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jadwalPeralatan ?? [] as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->penjadwalan->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $item->penjadwalan->judul_kegiatan }}</td>
                            <td>{{ $item->peralatan->nama_peralatan }}</td>
                            <td>{{ $item->jumlah }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</main>

<!-- JS -->
<script>
    const titles = {
        jadwal: "Rekap Jadwal & Presensi",
        peralatan: "Rekap Peralatan"
    };

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {

            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');

            this.classList.add('active');

            let tab = this.dataset.tab;
            document.getElementById('tab-' + tab).style.display = 'block';

            document.getElementById('tab-title').innerText = titles[tab];
        });
    });
</script>
@endsection