<?php
namespace App\Http\Controllers;
use App\Models\Peralatan;
use App\Models\User;
use Illuminate\Http\Request;

class PeralatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peralatan::query();
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_peralatan', 'like', "%$search%")
                ->orWhere('lokasi_penyimpanan', 'like', "%$search%");
            });
        }

        $peralatan = $query->get();
        // Filter status (karena bukan kolom db)
        if ($request->status == 'tersedia') {
            $peralatan = $peralatan->filter(function ($item) {
                return $item->stok_tersedia > 0;
            });
        }
        if ($request->status == 'tidak-tersedia') {
            $peralatan = $peralatan->filter(function ($item) {
                return $item->stok_tersedia <= 0;
            });
        }

        $peralatan = $peralatan->sortByDesc('stok_tersedia')->values();
        // pagination manual karena status nya tidak masuk ke db 
        $peralatan = new \Illuminate\Pagination\LengthAwarePaginator(
            $peralatan->forPage(request()->page ?? 1, 5),
            $peralatan->count(),
            5,
            request()->page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.peralatan.index', compact('peralatan'));
    }
    public function create()
    {
        $last = Peralatan::orderBy('id_peralatan', 'desc')->first();
        if ($last) {
            $lastNumber = (int) substr($last->id_peralatan, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'PR-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        return view('admin.peralatan.create', compact('newId'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_peralatan' => 'required|string|max:100',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
        ]);     
        $last = Peralatan::orderBy('id_peralatan', 'desc')->first();
        if ($last) {
            $lastNumber = (int) substr($last->id_peralatan, 3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'PR-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        Peralatan::create([
            'id_peralatan' => $newId,
            'nama_peralatan' => $request->nama_peralatan,
            'lokasi_penyimpanan' => $request->lokasi_penyimpanan,
            'stok' => $request->stok,
        ]);
        return redirect()->route('admin.peralatan.index')->with('success', 'Peralatan berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        return view('admin.peralatan.edit', compact('peralatan'));
    }
    public function update(Request $request, $id)
    {
        $peralatan = Peralatan::findOrFail($id);
        $dipakai = $peralatan->dipakai;
        $request->validate([
            'nama_peralatan' => 'required|string|max:100',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'rusak' => 'nullable|integer|min:0',
            'perbaikan' => 'nullable|integer|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);
        if ($request->stok < $dipakai) {
            return back()->withErrors([
                'stok' => 'Stok tidak boleh lebih kecil dari jumlah yang sedang digunakan (' . $dipakai . ').'
            ])->withInput();
        }
        if (($request->rusak + $request->perbaikan + $dipakai) > $request->stok) {
            return back()->withErrors([
                'stok' => 'Jumlah rusak / sedang perbaikan tidak boleh melebihi stok.'
            ])->withInput();
        }
        $peralatan->update([
            'nama_peralatan' => $request->nama_peralatan,
            'lokasi_penyimpanan' => $request->lokasi_penyimpanan,
            'stok' => $request->stok,
            'rusak' => $request->rusak,
            'perbaikan' => $request->perbaikan,
            'keterangan' => $request->keterangan,
        ]);
        return redirect()->route('admin.peralatan.index')
            ->with('success', 'Peralatan berhasil diperbarui!');
    }
    public function destroy($id)
    {
        $peralatan = Peralatan::findOrFail($id);
        if ($peralatan->dipakai > 0) {
            return back()->with('error', 'Peralatan tidak dapat dihapus karena sedang digunakan dalam jadwal.');
        }
        $peralatan->delete();
        return redirect()->route('admin.peralatan.index')
            ->with('success', 'Peralatan berhasil dihapus!');
    }
}