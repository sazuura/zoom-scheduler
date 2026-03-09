<?php
namespace App\Http\Controllers;
use App\Models\Penjadwalan;
use App\Models\User;
use App\Models\Peralatan;
use App\Models\JadwalPeralatan;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    private $platforms = [
        'Online (Zoom/Google Meet)',
        'Offline (Di ruangan)'
    ];
    public function index(Request $request)
    {
        $query = Penjadwalan::with('absensi.user');
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_kegiatan', 'like', "%$search%")
                ->orWhere('tanggal', 'like', "%$search%")
                ->orWhereRaw("DAYNAME(tanggal) LIKE ?", ["%$search%"])
                ->orWhereHas('absensi.user', function ($u) use ($search) {
                        $u->where('nama_user', 'like', "%$search%");
                });
            });
        }
        if ($request->platform == 'online') {
            $query->where('platform', 'like', '%Online%');
        } 
        elseif ($request->platform == 'offline') {
            $query->where('platform', 'like', '%Offline%');
        }
        $jadwal = $query->orderBy('tanggal', 'desc')
                        ->paginate(5)
                        ->withQueryString();
        return view('admin.jadwal.index', compact('jadwal'));
    }
    public function create()
    {
        $operators = User::where('role', 'operator')->where('status', 'active')->get();
        $peralatans = Peralatan::all();
        $last = Penjadwalan::orderBy('id_penjadwalan', 'desc')->first();
        $newNumber = $last ? ((int) substr($last->id_penjadwalan, 2)) + 1 : 1;
        $newId = 'PJ' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        return view('admin.jadwal.create', [
            'operators' => $operators,
            'newId' => $newId,
            'platforms' => $this->platforms,
            'peralatans' => $peralatans
        ]);
    }
    public function store(Request $request)
    {
        $request->merge([
            'waktu_mulai' => substr($request->waktu_mulai, 0, 5),
            'waktu_selesai' => substr($request->waktu_selesai, 0, 5),
        ]);
        $request->validate([
            'judul_kegiatan' => 'required|string|max:150',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'platform' => 'required|string|max:50',
            'keterangan' => 'required|string|max:150',
            'id_user' => 'required|array',
            'id_user.*' => 'exists:users,id_user',
        ]);
        // cek bentrok operator
        foreach ($request->id_user as $operatorId) {
            $bentrok = Penjadwalan::whereHas('absensi', function ($q) use ($operatorId) {
                    $q->where('id_user', $operatorId);
                })
                ->whereDate('tanggal', $request->tanggal)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('waktu_mulai', [$request->waktu_mulai, $request->waktu_selesai])
                        ->orWhereBetween('waktu_selesai', [$request->waktu_mulai, $request->waktu_selesai])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('waktu_mulai', '<=', $request->waktu_mulai)
                                ->where('waktu_selesai', '>=', $request->waktu_selesai);
                        });
                })
                ->exists();
            if ($bentrok) {
                $operator = User::find($operatorId);
                return back()
                    ->withInput()
                    ->with('error', 'Operator '.$operator->nama_user.' sudah memiliki jadwal pada waktu tersebut.');
            }
        }
        // generate ID jadwal
        $last = Penjadwalan::orderBy('id_penjadwalan', 'desc')->first();
        $newNumber = $last ? ((int) substr($last->id_penjadwalan, 2)) + 1 : 1;
        $newId = 'PJ' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        DB::beginTransaction();
        try {
            // simpan jadwal
            $jadwal = Penjadwalan::create([
                'id_penjadwalan' => $newId,
                'judul_kegiatan' => $request->judul_kegiatan,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'platform' => $request->platform,
                'keterangan' => $request->keterangan
            ]);
            // simpan operator ke tabel absensi
            foreach ($request->id_user as $operatorId) {
                Absensi::create([
                    'id_penjadwalan' => $jadwal->id_penjadwalan,
                    'id_user' => $operatorId,
                    'tanggal' => $request->tanggal,
                    'status' => 'pending',
                    'keterangan' => null,
                    'validated' => 0
                ]);
            }
            // simpan peralatan
            if ($request->peralatan) {
                foreach ($request->peralatan as $index => $idPeralatan) {
                    if ($idPeralatan && $request->jumlah[$index]) {
                        $alat = Peralatan::find($idPeralatan);
                        if ($request->jumlah[$index] > $alat->stok_tersedia) {
                            throw new \Exception('Stok peralatan '.$alat->nama_peralatan.' tidak mencukupi.');
                        }
                        JadwalPeralatan::create([
                            'id_penjadwalan' => $jadwal->id_penjadwalan,
                            'id_peralatan' => $idPeralatan,
                            'jumlah' => $request->jumlah[$index],
                            'status_pemasangan' => 'belum_dipasang'
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan!');
    }
    public function edit($id)
    {
        $jadwal = Penjadwalan::with([
            'absensi.user',
            'jadwalPeralatan.peralatan'
        ])->findOrFail($id);
        $operators = User::all();
        $peralatans = Peralatan::all();
        $selectedOperators = $jadwal->absensi->pluck('id_user')->toArray();
        $platforms = [
            'Online (Zoom/Google Meet)',
            'Offline (Di ruangan)'
        ];
        return view('admin.jadwal.edit', compact(
            'jadwal',
            'operators',
            'peralatans',
            'selectedOperators',
            'platforms'
        ));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:150',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'platform' => 'required|string|max:50',
            'keterangan' => 'required|string|max:150',
            'id_user' => 'required|array',
            'id_user.*' => 'nullable|exists:users,id_user',
        ]);
        $operators = array_filter($request->id_user);
        $jadwal = Penjadwalan::findOrFail($id);
        DB::beginTransaction();
        try {
            $jadwal->update([
                'judul_kegiatan' => $request->judul_kegiatan,
                'tanggal' => $request->tanggal,
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'platform' => $request->platform,
                'keterangan' => $request->keterangan,
            ]);
            // UPDATE OPERATOR
            Absensi::where('id_penjadwalan', $jadwal->id_penjadwalan)->delete();
            foreach ($operators as $operatorId) {
                Absensi::create([
                    'id_penjadwalan' => $jadwal->id_penjadwalan,
                    'id_user' => $operatorId,
                    'tanggal' => $request->tanggal,
                    'status' => 'terjadwal'
                ]);
            }
            // UPDATE PERALATAN
            JadwalPeralatan::where('id_penjadwalan', $jadwal->id_penjadwalan)->delete();
            if ($request->peralatan) {
                foreach ($request->peralatan as $index => $idPeralatan) {
                    if ($idPeralatan && $request->jumlah[$index]) {
                        $alat = Peralatan::find($idPeralatan);
                        if ($request->jumlah[$index] > $alat->stok_tersedia) {
                            throw new \Exception('Stok peralatan '.$alat->nama_peralatan.' tidak mencukupi.');
                        }
                        JadwalPeralatan::create([
                            'id_penjadwalan' => $jadwal->id_penjadwalan,
                            'id_peralatan' => $idPeralatan,
                            'jumlah' => $request->jumlah[$index],
                            'status_pemasangan' => 'belum_dipasang'
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
        return redirect()
            ->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }
    public function destroy($id)
    {
        $jadwal = Penjadwalan::findOrFail($id);
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
    public function show($id)
    {
        $jadwal = Penjadwalan::with([
            'absensi.user',
            'jadwalPeralatan.peralatan'
        ])->findOrFail($id);
        return view('admin.jadwal.detail', compact('jadwal'));
    }
}