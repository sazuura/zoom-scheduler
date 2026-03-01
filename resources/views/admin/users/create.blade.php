@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Tambah User</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li><i class="bx bx-chevron-right"></i></li>
                <li><a class="active" href="#">Tambah</a></li>
            </ul>
        </div>
    </div>

    {{-- Area form --}}
    <div class="table-data">
        <div class="order" style="flex:1;">
            <div class="head">
                <h3>Form Tambah User</h3>
            </div>

            {{-- Validasi --}}
            @if ($errors->any())
                <div style="background:#fff3cd;border:1px solid #ffeeba;padding:12px;border-radius:8px;margin-bottom:16px;">
                    <ul style="margin:0;padding-left:18px;color:#856404;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
                @csrf

                {{-- ID User (readonly, auto) --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label for="id_user">ID User</label>
                    <input type="text" id="id_user" name="id_user" class="form-control" value="{{ $newId ?? '' }}" readonly
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#f5f5f5;">
                </div>

                {{-- Nama --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label for="nama_user">Nama User</label>
                    <input type="text" id="nama_user" name="nama_user" class="form-control" value="{{ old('nama_user') }}" required
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                </div>

                {{-- Email --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required
                           style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                </div>

                {{-- Password with eye toggle --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label for="password">Password</label>
                    <div style="display:flex; gap:8px;">
                        <input type="password" id="password" name="password" class="form-control" required
                               style="flex:1; padding:8px 10px; border-radius:6px; border:1px solid #ddd;">
                        <button type="button" class="btn-toggle-eye" onclick="togglePassword('password','toggleIcon1')"
                                style="padding:8px 12px; border-radius:6px; border:1px solid #ddd; background:#fff; cursor:pointer;">
                            <i id="toggleIcon1" class="bx bx-hide" style="font-size:18px;"></i>
                        </button>
                    </div>
                </div>

                {{-- Role --}}
                <div class="mb-3" style="margin-bottom:12px;">
                    <label for="role">Role</label>
                    <select id="role" name="role" required
                            style="width:100%; padding:8px 10px; border-radius:6px; border:1px solid #ddd; background:#fff;">
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                    </select>
                </div>

                {{-- Tombol --}}
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:18px;">
                    <a href="{{ route('admin.users.index') }}" class="btn" style="padding:8px 14px; background:#e0e0e0; border-radius:6px; text-decoration:none; color:#222;">Batal</a>
                    <button type="submit" class="btn btn-primary" style="padding:8px 14px; background:#3C91E6; color:#fff; border:none; border-radius:6px;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</main>

{{-- Inline script kecil untuk toggle password (bisa pindah ke public/js/adminhub.js jika mau) --}}
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (!input) return;

    if (input.type === "password") {
        input.type = "text";
        // toggle Boxicons (if loaded)
        if (icon && icon.classList.contains('bx')) {
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show');
        }
        // fallback for FontAwesome (if used)
        if (icon && icon.classList.contains('fa')) {
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    } else {
        input.type = "password";
        if (icon && icon.classList.contains('bx')) {
            icon.classList.remove('bx-show');
            icon.classList.add('bx-hide');
        }
        if (icon && icon.classList.contains('fa')) {
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}
</script>
@endsection
