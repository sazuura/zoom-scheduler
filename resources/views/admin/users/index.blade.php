@extends('layouts.admin')
@section('title', 'Data Users')
@section('content')
    <main>
        @if(session('success'))
            <div id="toast" class="toast toast-success">
                <i class="bx bx-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div id="toast" class="toast toast-error">
                <i class="bx bx-error-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="head-title">
            <div class="left">
                <h1>Data Users</h1>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-download">
                <i class="bx bx-plus"></i>
                <span class="text">Tambah User</span>
            </a>
        </div>

        <div class="toolbar">
            <form method="GET" action="{{ route('admin.users.index') }}" class="toolbar-form">

                <div class="search-box">
                    <i class="bx bx-search"></i>
                    <input type="text" name="search" placeholder="Cari user..." value="{{ request('search') }}">
                </div>

                <div class="filter-group">
                    <select name="status">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>

                    <button type="submit" class="btn-apply">
                        Terapkan
                    </button>

                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.users.index') }}" class="btn-clear">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
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
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr class="{{ $user->status === 'inactive' ? 'row-inactive' : '' }}">
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->nama_user }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>

                                <td>
                                    @if($user->status === 'active')
                                        <span class="badge badge-active">
                                            <i class="bx bx-check-circle"></i> Active
                                        </span>
                                    @else
                                        <span class="badge badge-inactive">
                                            <i class="bx bx-x-circle"></i> Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="action-buttons">

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.users.edit', $user->id_user) }}" class="btn-action info"
                                            title="Edit User">
                                            <i class="bx bx-edit"></i>
                                        </a>

                                        {{-- Toggle Status --}}
                                        <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirmAction('{{ $user->status }}')">
                                            @csrf
                                            @method('DELETE')

                                            @if($user->status === 'active')
                                                <button type="submit" class="btn-action danger" title="Nonaktifkan User">
                                                    <i class="bx bx-user-x"></i>
                                                </button>
                                            @else
                                                <button type="submit" class="btn-action success" title="Aktifkan User">
                                                    <i class="bx bx-user-check"></i>
                                                </button>
                                            @endif
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($users->hasPages())
                    <div class="pagination-clean">

                        {{-- Previous --}}
                        @if ($users->onFirstPage())
                            <span class="page-btn disabled">
                                <i class="bx bx-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="page-btn">
                                <i class="bx bx-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if ($page == $users->currentPage())
                                <span class="page-btn active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="page-btn">
                                <i class="bx bx-chevron-right"></i>
                            </a>
                        @else
                            <span class="page-btn disabled">
                                <i class="bx bx-chevron-right"></i>
                            </span>
                        @endif

                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- CONFIRM CUSTOM --}}
    <script>
        function confirmAction(status) {
            if (status === 'active') {
                return confirm('Yakin ingin menonaktifkan user ini?');
            } else {
                return confirm('Aktifkan kembali user ini?');
            }
        }

        // Auto hide toast
        setTimeout(() => {
            let toast = document.getElementById('toast');
            if (toast) {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
        }, 3000);
    </script>
@endsection