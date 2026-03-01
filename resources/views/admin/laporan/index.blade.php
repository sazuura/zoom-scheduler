@extends('layouts.admin')

@section('title', 'Laporan Penjadwalan')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Laporan Penjadwalan</h1>
        </div>
    </div>

    <div class="card" style="margin-bottom:20px; border-radius:8px; border:1px solid #ddd;">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.index') }}">
                <div class="row" style="display:flex; flex-wrap:wrap; gap:16px;">

                    <div class="col" style="flex:1; min-width:200px;">
                        <label class="form-label" style="font-size:13px; font-weight:600;">Dari Tanggal</label>
                        <input type="date" name="start" value="{{ request('start') }}"
                               class="form-control"
                               style="width:100%; padding:8px 10px; border:1px solid #ddd; border-radius:6px;">
                    </div>

                    <div class="col" style="flex:1; min-width:200px;">
                        <label class="form-label" style="font-size:13px; font-weight:600;">Sampai Tanggal</label>
                        <input type="date" name="end" value="{{ request('end') }}"
                               class="form-control"
                               style="width:100%; padding:8px 10px; border:1px solid #ddd; border-radius:6px;">
                    </div>

                    <div class="col" style="flex:1; min-width:200px;">
                        <label class="form-label" style="font-size:13px; font-weight:600;">Operator</label>
                        <select name="operator" class="form-select"
                                style="width:100%; padding:8px 10px; border:1px solid #ddd; border-radius:6px;">
                            <option value="">Semua</option>
                            @foreach($operators as $op)
                                <option value="{{ $op->id_user }}" {{ request('operator') == $op->id_user ? 'selected' : '' }}>
                                    {{ $op->nama_user }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col" style="align-self:flex-end;">
                        <button type="submit" class="btn-download"
                                style="padding:8px 18px; background:#3C91E6; color:#fff; border:none; border-radius:6px; font-weight:600;">
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<div class="col" style="align-self:flex-end; display:flex; gap:10px;">
    <a href="{{ route('admin.laporan.exportPdf', request()->all()) }}" class="btn"
       style="padding:8px 18px; background:#DB504A; color:#fff; border-radius:6px; text-decoration:none;">
        Export PDF
    </a>
    <a href="{{ route('admin.laporan.exportExcel', request()->all()) }}" class="btn"
       style="padding:8px 18px; background:#3C91E6; color:#fff; border-radius:6px; text-decoration:none;">
        Export Excel
    </a>
</div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Rekap Jadwal & Absensi</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul Kegiatan</th>
                        <th>Operator</th>
                        <th>Waktu</th>
                        <th>Status Absensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jadwal as $j)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($j->tanggal)->format('d/m/Y') }}</td>
                            <td>{{ $j->judul_kegiatan }}</td>
                            <td>{{ $j->user->nama_user ?? '-' }}</td>
                            <td>{{ $j->waktu_mulai }} - {{ $j->waktu_selesai }}</td>
                            <td>
                                @php
                                    $absen = $j->absensi->first();
                                @endphp
                                @if($absen)
                                    {{ ucfirst($absen->status) }}
                                    @if($absen->validated)
                                        <span style="color:green;">(Valid)</span>
                                    @else
                                        <span style="color:orange;">(Menunggu)</span>
                                    @endif
                                @else
                                    <span style="color:red;">Belum Absen</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data jadwal.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
