<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penjadwalan;
use App\Models\Absensi;
use App\Models\Peralatan;
use App\Models\Dokumentasi;
use Barryvdh\DomPDF\Facade\Pdf; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahOperator    = User::where('role','operator')->where('status','active')->count();
        $jumlahPenjadwalan = Penjadwalan::count();
        $jumlahPeralatan   = Peralatan::count();
        $statusList = [
            'hadir',
            'izin_disetujui',
            'sakit_disetujui',
            'alpha'
        ];
        $absensi = Absensi::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total','status');
        $statusCounts = collect($statusList)
            ->mapWithKeys(fn($s) => [$s => $absensi[$s] ?? 0]);
        $operatorData = User::where('role','operator')
            ->withCount('penjadwalan')
            ->get();
        $operatorLabels = $operatorData->pluck('nama_user');
        $operatorCounts = $operatorData->pluck('penjadwalan_count');
        $records = Absensi::selectRaw('tanggal, status, COUNT(*) as total')
            ->groupBy('tanggal','status')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');
        $tanggalLabels = [];
        $chartData = [];
        foreach ($statusList as $status) {
            $chartData[$status] = [];
        }
        foreach ($records as $tanggal => $items) {
            $tanggalLabels[] = \Carbon\Carbon::parse($tanggal)->format('d/m');
            $map = $items->pluck('total','status');
            foreach ($statusList as $status) {
                $chartData[$status][] = $map[$status] ?? 0;
            }
        }
        return view('admin.dashboard',[
            'jumlahOperator'    => $jumlahOperator,
            'jumlahPenjadwalan' => $jumlahPenjadwalan,
            'jumlahPeralatan'   => $jumlahPeralatan,

            'hadir' => $statusCounts['hadir'],
            'izin'  => $statusCounts['izin_disetujui'],
            'sakit' => $statusCounts['sakit_disetujui'],
            'alpha' => $statusCounts['alpha'],

            'operatorLabels' => $operatorLabels,
            'operatorCounts' => $operatorCounts,
            'operatorData'   => $operatorData,

            'tanggalLabels' => $tanggalLabels,
            'hadirData'     => $chartData['hadir'],
            'izinData'      => $chartData['izin_disetujui'],
            'sakitData'     => $chartData['sakit_disetujui'],
            'alphaData'     => $chartData['alpha'],
        ]);
    }
    // index untuk absensi
    public function index(Request $request)
    {
        $query = Absensi::with(['penjadwalan','user']);
        // SEARCH kegiatan atau operator
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('penjadwalan', function ($q2) use ($search) {
                    $q2->where('judul_kegiatan', 'like', "%$search%");
                })
                ->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('nama_user', 'like', "%$search%");
                });
            });
        }
        // FILTER STATUS
        if ($request->status) {
            if ($request->status == 'hadir') {
                $query->where('status', 'hadir');
            }
            if ($request->status == 'tidak_hadir') {
                $query->whereIn('status', [
                    'izin_disetujui',
                    'sakit_disetujui',
                    'alpha'
                ]);
            }
            if ($request->status == 'perlu_disetujui') {
                $query->whereIn('status', [
                    'izin',
                    'sakit'
                ]);
            }
            if ($request->status == 'pending') {
                $query->whereIn('status', [
                    'pending',
                    'ditolak'
                ]);
            }
        }
        $absensi = $query
            ->orderBy('tanggal','desc')
            ->paginate(10)
            ->withQueryString();
        return view('admin.absensi.index', compact('absensi'));
    }
    public function updateStatus($id, $status)
    {
        $absen = Absensi::findOrFail($id);
        $absen->update([
            'status' => $status
        ]);
        return redirect()->route('admin.absensi.index')
            ->with('success', 'Status absensi berhasil diperbarui.');
    }
    public function show($id)
    {
        $absensi = Absensi::with(['user','dokumentasi','penjadwalan'])->findOrFail($id);
        return view('admin.absensi.detail', compact('absensi'));
    }
    public function laporan(Request $request)
    {
        $query = Absensi::with([
            'user',
            'penjadwalan'
        ]);
        if ($request->start) {
            $query->whereDate('tanggal', '>=', $request->start);
        }
        if ($request->end) {
            $query->whereDate('tanggal', '<=', $request->end);
        }
        if ($request->operator) {
            $query->where('id_user', $request->operator);
        }
        $absensi = $query->orderBy('tanggal','desc')->get();
        $operators = User::where('role','operator')->get();
        return view('admin.laporan.index', compact('absensi','operators'));
    }
    public function exportPdf(Request $request)
    {
        $query = Penjadwalan::with(['user','absensi']);

        if ($request->start) $query->whereDate('tanggal','>=',$request->start);
        if ($request->end) $query->whereDate('tanggal','<=',$request->end);
        if ($request->operator) $query->where('id_user',$request->operator);

        $jadwal = $query->orderBy('tanggal','desc')->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('jadwal'));
        return $pdf->download('laporan-penjadwalan.pdf');
    }
    public function exportExcel(Request $request)
    {
        return Excel::download(new LaporanExport($request), 'laporan-penjadwalan.xlsx');
    }
}
