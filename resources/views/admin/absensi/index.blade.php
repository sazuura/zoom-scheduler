@extends('layouts.admin')

@section('title', 'Validasi Absensi')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Validasi Absensi</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Data Absensi</h3>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Operator</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Validasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $absen)
                        <tr>
                            <td>{{ $absen->tanggal?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $absen->user->nama_user ?? '-' }}</td>
                            <td>
                                {{ $absen->penjadwalan->judul_kegiatan ?? '-' }}
                                @if($absen->penjadwalan?->waktu_mulai && $absen->penjadwalan?->waktu_selesai)
                                    ({{ $absen->penjadwalan->waktu_mulai }} - {{ $absen->penjadwalan->waktu_selesai }})
                                @endif
                            </td>
                            <td>{{ ucfirst($absen->status) }}</td>
                            <td>{{ $absen->keterangan ?? '-' }}</td>
                            <td>
                                @if($absen->validated)
                                    <span style="color: green;">✔ Valid</span>
                                @else
                                    <span style="color: orange;">Menunggu</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:6px;">
                                    @if(!$absen->validated)
                                        <form action="{{ route('admin.absensi.validate', $absen->id_absensi) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-download">Validasi</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.absensi.unvalidate', $absen->id_absensi) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-action edit">Batalkan</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.absensi.destroy', $absen->id_absensi) }}" method="POST" onsubmit="return confirm('Hapus absensi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action delete">Hapus</button>
                                    </form>
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
        </div>
    </div>
</main>
@endsection
