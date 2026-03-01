@extends('layouts.operator')

@section('title', 'Peralatan')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Peralatan</h1>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Daftar Peralatan</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Peralatan</th>
                        <th>Nama Peralatan</th>
                        <th>Kondisi</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peralatan as $p)
                        <tr>
                            <td>{{ $p->id_peralatan }}</td>
                            <td>{{ $p->nama_peralatan }}</td>
                            <td>
                                @if($p->kondisi == 'Baik')
                                    <span class="status completed">Baik</span>
                                @elseif($p->kondisi == 'Diperbaiki')
                                    <span class="status pending">Diperbaiki</span>
                                @else
                                    <span class="status process">{{ $p->kondisi }}</span>
                                @endif
                            </td>
                            <td>{{ $p->stok }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data peralatan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
