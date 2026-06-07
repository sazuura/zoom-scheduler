@extends('layouts.app')
@section('title', 'Edit Jadwal')
@section('sidebar-menu') <x-sidebar-admin /> @endsection

@section('content')
<main>
    <div class="head-title">
        <div class="left"><h1>Edit Jadwal</h1></div>
        <a href="{{ route('admin.jadwal.index') }}" class="toolbar-btn neutral">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
    </div>

    @if($errors->any())
    <div style="background:#fdecea;border-left:4px solid #e74c3c;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;color:#c0392b;">
        <ul style="margin:0;padding-left:18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.jadwal.update', $jadwal->id_penjadwalan) }}" method="POST">
        @csrf @method('PUT')

        <div class="form-card">
            <h3><i class="bx bx-info-circle"></i> Informasi Jadwal</h3>
            <div class="form-grid">
                <div class="form-group span-2">
                    <label class="form-label">Judul Kegiatan <span class="req">*</span></label>
                    <input type="text" name="judul_kegiatan" class="form-input"
                        value="{{ old('judul_kegiatan', $jadwal->judul_kegiatan) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal <span class="req">*</span></label>
                    <input type="date" name="tanggal" class="form-input"
                        value="{{ old('tanggal', $jadwal->tanggal->format('Y-m-d')) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Platform <span class="req">*</span></label>
                    <select name="platform" class="form-select" required>
                        @foreach(['Online (Zoom)','Online (Google Meet)','Offline','Hybrid'] as $p)
                            <option value="{{ $p }}" {{ old('platform',$jadwal->platform)==$p?'selected':'' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu Mulai <span class="req">*</span></label>
                    <input type="time" name="waktu_mulai" class="form-input"
                        value="{{ old('waktu_mulai', substr($jadwal->waktu_mulai,0,5)) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu Selesai <span class="req">*</span></label>
                    <input type="time" name="waktu_selesai" class="form-input"
                        value="{{ old('waktu_selesai', substr($jadwal->waktu_selesai,0,5)) }}" required>
                </div>
                <div class="form-group span-2">
                    <label class="form-label">Keterangan</label>
                    <input type="text" name="keterangan" class="form-input"
                        value="{{ old('keterangan', $jadwal->keterangan) }}">
                </div>
            </div>
        </div>

        <div class="form-card">
            <h3><i class="bx bxs-group"></i> Operator Bertugas</h3>
            <div class="dynamic-list" id="operator-list">
                @foreach($selectedOperators as $idUser)
                <div class="dynamic-item">
                    <select name="operator_ids[]" class="form-select" required>
                        <option value="" disabled>-- Pilih Operator --</option>
                        @foreach($operators as $op)
                            <option value="{{ $op->id_user }}" {{ $op->id_user==$idUser?'selected':'' }}>{{ $op->nama_user }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn-remove" onclick="removeItem(this)"><i class="bx bx-trash"></i></button>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn-add-item" id="add-operator"><i class="bx bx-plus"></i> Tambah Operator</button>
        </div>

        <div class="form-card">
            <h3><i class="bx bxs-wrench"></i> Peralatan</h3>
            <div class="dynamic-list" id="peralatan-list">
                @forelse($selectedPeralatan as $jp)
                <div class="dynamic-item">
                    <select name="peralatan_ids[]" class="form-select">
                        <option value="">-- Pilih Peralatan --</option>
                        @foreach($peralatans->groupBy('gedung') as $gedung => $items)
                            <optgroup label="{{ $gedung }}">
                                @foreach($items as $alat)
                                    <option value="{{ $alat->id_peralatan }}"
                                        {{ $alat->id_peralatan==$jp->id_peralatan?'selected':'' }}>
                                        {{ $alat->nama_peralatan }} — stok: {{ $alat->stok_tersedia }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <input type="number" name="peralatan_jumlah[]" class="form-input" min="1" value="{{ $jp->jumlah }}" style="flex:0 0 80px;">
                    <button type="button" class="btn-remove" onclick="removeItem(this)"><i class="bx bx-trash"></i></button>
                </div>
                @empty
                <div class="dynamic-item">
                    <select name="peralatan_ids[]" class="form-select">
                        <option value="">-- Pilih Peralatan (opsional) --</option>
                        @foreach($peralatans->groupBy('gedung') as $gedung => $items)
                            <optgroup label="{{ $gedung }}">
                                @foreach($items as $alat)
                                    <option value="{{ $alat->id_peralatan }}">{{ $alat->nama_peralatan }} — stok: {{ $alat->stok_tersedia }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <input type="number" name="peralatan_jumlah[]" class="form-input" min="1" placeholder="Jml" style="flex:0 0 80px;">
                    <button type="button" class="btn-remove" onclick="removeItem(this)"><i class="bx bx-trash"></i></button>
                </div>
                @endforelse
            </div>
            <button type="button" class="btn-add-item" id="add-peralatan"><i class="bx bx-plus"></i> Tambah Peralatan</button>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.jadwal.index') }}" class="btn-cancel">Batal</a>
            <button type="submit" class="btn-submit"><i class="bx bx-save"></i> Simpan Perubahan</button>
        </div>
    </form>
</main>
@endsection

@push('scripts')
<script>
function removeItem(btn) {
    var list = btn.closest('.dynamic-list');
    if (list.children.length > 1) btn.closest('.dynamic-item').remove();
}
function cloneItem(listId) {
    var list  = document.getElementById(listId);
    var clone = list.querySelector('.dynamic-item').cloneNode(true);
    clone.querySelectorAll('input,select').forEach(function(el){ el.value=''; });
    clone.querySelector('.btn-remove').onclick = function(){ removeItem(this); };
    list.appendChild(clone);
}
document.getElementById('add-operator').addEventListener('click',  function(){ cloneItem('operator-list'); });
document.getElementById('add-peralatan').addEventListener('click', function(){ cloneItem('peralatan-list'); });
</script>
@endpush
