@extends('layouts.admin')

@section('title', 'Data Users')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Data Users</h1>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-download">
            <i class="bx bx-plus"></i>
            <span class="text">Tambah User</span>
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>List User</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->nama_user }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <div class="action-buttons">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.users.edit', $user->id_user) }}" 
                                   class="btn-action edit">
                                    <i class="bx bx-edit"></i>
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.users.destroy', $user->id_user) }}" 
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete" 
                                            onclick="return confirm('Yakin ingin menghapus user ini?')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
