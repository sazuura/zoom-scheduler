@extends('layouts.operator')
@section('title', 'Ajukan Peminjaman')
@section('content')
<main>
    <div class="head-title">
        <div class="left">
            <h1>Ajukan Peminjaman</h1>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-data">
        <div class="order" style="max-width:560px;">
            <div class="head"><h3>Form Pengajuan</h3></div>
            <div style="padding:1.25rem;">
                <form action="{{ route('operator.peminjaman.store') }}" method="POST">
                    @csrf

                    {{-- Pilih Peralatan --}}
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="font-size:.85rem; font-weight:500; display:block; margin-bottom:.4rem;">
                            Peralatan <span style="color:red;">*</span>
                        </label>
                        <select name="id_peralatan" required style="width:100%; padding:.5rem .75rem; border-radius:8px; border:1px solid #ddd;">
                            <option value="">-- Pilih peralatan --</option>
                            @foreach($peralatan as $item)
                                <option value="{{ $item->id_peralatan }}"
                                    {{ old('id_peralatan') == $item->id_peralatan ? 'selected' : '' }}>
                                    {{ $item->nama_peralatan }}
                                    ({{ $item->stok_tersedia }} tersedia)
                                </option>
                            @endforeach
                        </select>
                        @error('id_peralatan')
                            <p style="color:red; font-size:.8rem; margin-top:.3rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Jumlah --}}
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="font-size:.85rem; font-weight:500; display:block; margin-bottom:.4rem;">
                            Jumlah <span style="color:red;">*</span>
                        </label>
                        <input type="number" name="jumlah" min="1" value="{{ old('jumlah', 1) }}" required
                               style="width:100%; padding:.5rem .75rem; border-radius:8px; border:1px solid #ddd;">
                        @error('jumlah')
                            <p style="color:red; font-size:.8rem; margin-top:.3rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Pinjam --}}
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="font-size:.85rem; font-weight:500; display:block; margin-bottom:.4rem;">
                            Tanggal Pinjam <span style="color:red;">*</span>
                        </label>
                        <input type="date" name="tanggal_pinjam"
                               value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}"
                               min="{{ now()->format('Y-m-d') }}" required
                               style="width:100%; padding:.5rem .75rem; border-radius:8px; border:1px solid #ddd;">
                        @error('tanggal_pinjam')
                            <p style="color:red; font-size:.8rem; margin-top:.3rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Kembali Rencana --}}
                    <div class="form-group" style="margin-bottom:1rem;">
                        <label style="font-size:.85rem; font-weight:500; display:block; margin-bottom:.4rem;">
                            Rencana Tanggal Kembali <span style="color:red;">*</span>
                        </label>
                        <input type="date" name="tanggal_kembali_rencana"
                               value="{{ old('tanggal_kembali_rencana') }}"
                               min="{{ now()->addDay()->format('Y-m-d') }}" required
                               style="width:100%; padding:.5rem .75rem; border-radius:8px; border:1px solid #ddd;">
                        @error('tanggal_kembali_rencana')
                            <p style="color:red; font-size:.8rem; margin-top:.3rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Keperluan --}}
                    <div class="form-group" style="margin-bottom:1.5rem;">
                        <label style="font-size:.85rem; font-weight:500; display:block; margin-bottom:.4rem;">
                            Keperluan <span style="color:red;">*</span>
                        </label>
                        <textarea name="keperluan" rows="3" required maxlength="255"
                                  placeholder="Jelaskan keperluan peminjaman..."
                                  style="width:100%; padding:.5rem .75rem; border-radius:8px; border:1px solid #ddd; resize:vertical;">{{ old('keperluan') }}</textarea>
                        @error('keperluan')
                            <p style="color:red; font-size:.8rem; margin-top:.3rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="display:flex; gap:.75rem;">
                        <button type="submit" class="btn-download" style="border:none; cursor:pointer;">
                            <i class="bx bx-send"></i>
                            <span class="text">Kirim Pengajuan</span>
                        </button>
                        <a href="{{ route('operator.peminjaman.index') }}" class="btn-clear" style="padding:.5rem 1rem;">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
