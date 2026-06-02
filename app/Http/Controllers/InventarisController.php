<?php
namespace App\Http\Controllers;
use App\Models\Peralatan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function dashboard()
    {
        $gedung = auth()->user()->gedung;
        $totalPeralatan = Peralatan::where('gedung', $gedung)->count();
        $totalTersedia  = Peralatan::where('gedung', $gedung)
            ->whereRaw('(stok - COALESCE(rusak,0) - COALESCE(perbaikan,0)) > 0')
            ->count();
        $totalRusak     = Peralatan::where('gedung', $gedung)->sum('rusak');

        $peralatanKritis = Peralatan::where('gedung', $gedung)
            ->whereRaw('(stok - COALESCE(rusak,0) - COALESCE(perbaikan,0)) BETWEEN 1 AND 2')
            ->orderByRaw('(stok - COALESCE(rusak,0) - COALESCE(perbaikan,0)) ASC')
            ->take(5)
            ->get();

        $peminjamanMenunggu = Peminjaman::with(['user', 'items.peralatan'])
            ->where('status', 'diajukan')
            ->whereHas('items.peralatan', fn($q) => $q->where('gedung', $gedung))
            ->orderBy('created_at')
            ->take(5)
            ->get();

        $totalMenunggu = Peminjaman::where('status', 'diajukan')
            ->whereHas('items.peralatan', fn($q) => $q->where('gedung', $gedung))
            ->count();

        return view('inventaris.dashboard', compact(
            'gedung',
            'totalPeralatan',
            'totalTersedia',
            'totalRusak',
            'peralatanKritis',
            'peminjamanMenunggu',
            'totalMenunggu',
        ));
    }

    public function peralatanIndex(Request $request)
    {
        $gedung = auth()->user()->gedung;
        $peralatan = Peralatan::where('gedung', $gedung)
            ->when($request->search, fn($q, $s) =>
                $q->where('nama_peralatan', 'like', "%{$s}%")
                  ->orWhere('kode_barang',  'like', "%{$s}%")
            )
            ->when($request->status, fn($q, $v) => match ($v) {
                'tersedia'       => $q->whereRaw('(stok - COALESCE(rusak,0) - COALESCE(perbaikan,0)) > 0'),
                'tidak_tersedia' => $q->whereRaw('(stok - COALESCE(rusak,0) - COALESCE(perbaikan,0)) <= 0'),
                'kritis'         => $q->whereRaw('(stok - COALESCE(rusak,0) - COALESCE(perbaikan,0)) BETWEEN 1 AND 2'),
                default          => $q,
            })
            ->orderBy('nama_peralatan')
            ->paginate(10)
            ->withQueryString();
        return view('inventaris.peralatan.index', compact('peralatan', 'gedung'));
    }
}
