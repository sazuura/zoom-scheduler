@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <main>
        <div class="head-title">
            <div class="left">
                <h1>Edit User</h1>
                <ul class="breadcrumb">
                    <li><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li><i class="bx bx-chevron-right"></i></li>
                    <li><a class="active" href="#">Edit</a></li>
                </ul>
            </div>
        </div>

        <div class="table-data">
            <div class="order" style="flex:1;">
                <div class="head">
                    <h3>Form Edit User</h3>
                </div>

                <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST">
                    @csrf @method('PUT')

                    {{-- ID User --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label for="id_user">ID User</label>
                        <input type="text" id="id_user" name="id_user" class="form-control" value="{{ $user->id_user }}"
                            readonly
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                    </div>

                    {{-- Nama --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label for="nama_user">Nama User</label>
                        <input type="text" id="nama_user" name="nama_user" class="form-control"
                            value="{{ $user->nama_user }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- NoHP --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label for="nohp">NoHP</label>
                        <input type="tel" id="nohp" name="nohp" class="form-control" value="{{ $user->nohp }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;"
                            pattern="[0-9+]+" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                    </div>

                    {{-- Email --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                    </div>

                    {{-- Password --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label for="passwordEdit">Password (kosongkan jika tidak diubah)</label>
                        <div style="display:flex; gap:8px;">
                            <input type="password" id="passwordEdit" name="password" class="form-control"
                                style="flex:1; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                            <button type="button" class="btn-toggle-eye"
                                onclick="togglePassword('passwordEdit','toggleIcon2')"
                                style="padding:8px 12px; border-radius:6px; border:1px solid #ddd; background:#fff; cursor:pointer;">
                                <i id="toggleIcon2" class="bx bx-hide" style="font-size:18px;"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Role --}}
                    <div class="mb-3" style="margin-bottom:12px;">
                        <label for="role">Role</label>
                        <select id="role" name="role" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator</option>
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:18px;">
                        <a href="{{ route('admin.users.index') }}" class="btn"
                            style="padding:8px 14px; background:#e0e0e0; border-radius:6px; text-decoration:none; color:#222;">Batal</a>
                        <button type="submit" class="btn btn-primary"
                            style="padding:8px 14px; background:#3C91E6; color:#fff; border:none; border-radius:6px;">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    {{-- Toggle password --}}
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = "password";
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }
    </script>
@endsection