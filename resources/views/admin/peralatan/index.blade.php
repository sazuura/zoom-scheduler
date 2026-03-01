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

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>List Peralatan</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID Peralatan</th>
                        <th>Nama Peralatan</th>
                        <th>Kondisi</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peralatan as $item)
                        <tr>
                            <td>{{ $item->id_peralatan }}</td>
                            <td>{{ $item->nama_peralatan }}</td>
                            <td>{{ $item->kondisi }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>
                                <div class="action-buttons">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.peralatan.edit', $item->id_peralatan) }}" class="btn-action edit">
                                        <i class="bx bx-edit"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.peralatan.destroy', $item->id_peralatan) }}" 
                                          method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action delete" onclick="return confirm('Yakin hapus peralatan ini?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
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
        </div>
    </div>
</main>
@endsection
