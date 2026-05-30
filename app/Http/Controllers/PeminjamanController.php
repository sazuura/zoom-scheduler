<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Peralatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // ─── Operator: lihat riwayat peminjaman milik sendiri ────────────────────

    public function operatorIndex(Request $request)
    {
        $userId = auth()->user()->id_user;

        $query = Peminjaman::with('peralatan')
            ->where('id_user', $userId)
            ->orderByDesc('created_at');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->paginate(10)->withQueryString();

        return view('operator.peminjaman.index', compact('peminjaman'));
    }

    // ─── Operator: form ajukan peminjaman ────────────────────────────────────

    public function operatorCreate()
    {
        // Hanya tampilkan peralatan yang stoknya tersedia
        $peralatan = Peralatan::get()->filter(fn($p) => $p->stok_tersedia > 0)->values();

        return view('operator.peminjaman.create', compact('peralatan'));
    }

    // ─── Operator: simpan pengajuan ──────────────────────────────────────────

    public function operatorStore(Request $request)
    {
        $request->validate([
            'id_peralatan'            => 'required|exists:peralatan,id_peralatan',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'keperluan'               => 'required|string|max:255',
        ], [
            'tanggal_pinjam.after_or_equal'          => 'Tanggal pinjam tidak boleh di masa lalu.',
            'tanggal_kembali_rencana.after'           => 'Tanggal kembali harus setelah tanggal pinjam.',
        ]);

        $peralatan = Peralatan::findOrFail($request->id_peralatan);

        // Cek stok cukup
        if ($peralatan->stok_tersedia < $request->jumlah) {
            return back()
                ->withInput()
                ->with('error', "Stok tidak cukup. Tersedia: {$peralatan->stok_tersedia} unit.");
        }

        Peminjaman::create([
            'id_user'                 => auth()->user()->id_user,
            'id_peralatan'            => $request->id_peralatan,
            'jumlah'                  => $request->jumlah,
            'tanggal_pinjam'          => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'keperluan'               => $request->keperluan,
            'status'                  => 'diajukan',
        ]);

        return redirect()
            ->route('operator.peminjaman.index')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan inventaris.');
    }

    // ─── Inventaris: lihat semua peminjaman ──────────────────────────────────

    public function inventarisIndex(Request $request)
    {
        $query = Peminjaman::with(['user', 'peralatan'])->orderByDesc('created_at');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->paginate(15)->withQueryString();

        return view('inventaris.peminjaman.index', compact('peminjaman'));
    }

    // ─── Inventaris: setujui peminjaman ──────────────────────────────────────

    public function inventarisApprove(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('peralatan')->findOrFail($id);

        if (! $peminjaman->isMenunggu()) {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $peralatan = $peminjaman->peralatan;

        if ($peralatan->stok_tersedia < $peminjaman->jumlah) {
            return back()->with('error', "Stok tidak cukup untuk disetujui. Tersedia: {$peralatan->stok_tersedia} unit.");
        }

        DB::transaction(function () use ($peminjaman, $peralatan) {
            // Kurangi stok
            $peralatan->decrement('stok', $peminjaman->jumlah);

            $peminjaman->update(['status' => 'disetujui']);
        });

        return back()->with('success', "Peminjaman oleh {$peminjaman->user->nama_user} berhasil disetujui.");
    }

    // ─── Inventaris: tolak peminjaman ────────────────────────────────────────

    public function inventarisReject(Request $request, $id)
    {
        $request->validate([
            'catatan_inventaris' => 'nullable|string|max:255',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if (! $peminjaman->isMenunggu()) {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $peminjaman->update([
            'status'              => 'ditolak',
            'catatan_inventaris'  => $request->catatan_inventaris ?? 'Ditolak oleh inventaris.',
        ]);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    // ─── Inventaris: konfirmasi pengembalian ─────────────────────────────────

    public function inventarisKembali(Request $request, $id)
    {
        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'catatan_inventaris'     => 'nullable|string|max:255',
        ]);

        $peminjaman = Peminjaman::with('peralatan')->findOrFail($id);

        if (! $peminjaman->isDisetujui()) {
            return back()->with('error', 'Hanya peminjaman berstatus "Disetujui" yang bisa dikembalikan.');
        }

        DB::transaction(function () use ($peminjaman, $request) {
            // Kembalikan stok
            $peminjaman->peralatan->increment('stok', $peminjaman->jumlah);

            $peminjaman->update([
                'status'                  => 'dikembalikan',
                'tanggal_kembali_aktual'  => $request->tanggal_kembali_aktual,
                'catatan_inventaris'      => $request->catatan_inventaris,
            ]);
        });

        return back()->with('success', 'Pengembalian barang berhasil dikonfirmasi.');
    }
}
