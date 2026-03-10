@extends('layouts.operator')
@section('title', 'Peralatan')
@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Peralatan Kegiatan</h1>
            </div>
        </div>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h3>Peralatan yang Digunakan</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Peralatan</th>
                            <th>Jumlah</th>
                            <th>Lokasi</th>
                            <th>Status Pemasangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peralatan as $item)
                            <tr>
                                <td>{{ $item->penjadwalan->judul_kegiatan ?? '-' }}</td>
                                <td>{{ $item->peralatan->nama_peralatan ?? '-' }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>{{ $item->penjadwalan->keterangan }}</td>
                                <td>
                                    @if($item->status_pemasangan == 'sudah_dipasang')
                                        <span class="badge badge-active">
                                            Sudah Dipasang
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            Belum Dipasang
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_pemasangan == 'belum_dipasang')
                                        <form action="{{ route('operator.peralatan.update', $item->id_jadwal_alat) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-action success"
                                                onclick="return confirm('Apakah peralatan ini sudah terpasang?')">
                                                <i class='bx bx-check'></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge badge-active">
                                            Tervalidasi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    Belum ada data peralatan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection