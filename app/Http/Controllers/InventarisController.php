<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function dashboard()
    {
        $totalPeralatan  = Peralatan::count();
        $totalTersedia   = Peralatan::get()->filter(fn($p) => $p->stok_tersedia > 0)->count();
        $totalHabis      = $totalPeralatan - $totalTersedia;
        $totalRusak      = Peralatan::sum('rusak');

        $peralatanKritis = Peralatan::get()
            ->filter(fn($p) => $p->stok_tersedia > 0 && $p->stok_tersedia <= 2)
            ->sortBy('stok_tersedia')
            ->take(5);

        // Sekarang sudah real — bukan placeholder
        $peminjamanMenunggu = Peminjaman::with(['user', 'peralatan'])
            ->where('status', 'diajukan')
            ->orderBy('created_at')
            ->take(5)
            ->get();

        $totalMenunggu = Peminjaman::where('status', 'diajukan')->count();

        return view('inventaris.dashboard', compact(
            'totalPeralatan',
            'totalTersedia',
            'totalHabis',
            'totalRusak',
            'peralatanKritis',
            'peminjamanMenunggu',
            'totalMenunggu'
        ));
    }

    public function peralatanIndex(Request $request)
    {
        $query = Peralatan::query();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peralatan', 'like', "%$search%")
                  ->orWhere('lokasi_penyimpanan', 'like', "%$search%");
            });
        }

        $semua = $query->get();

        $peralatan = match ($request->status) {
            'tersedia'       => $semua->filter(fn($p) => $p->stok_tersedia > 0),
            'tidak_tersedia' => $semua->filter(fn($p) => $p->stok_tersedia <= 0),
            'kritis'         => $semua->filter(fn($p) => $p->stok_tersedia > 0 && $p->stok_tersedia <= 2),
            default          => $semua,
        };

        $peralatan = $peralatan->sortByDesc('stok_tersedia')->values();

        return view('inventaris.peralatan.index', compact('peralatan'));
    }
}
