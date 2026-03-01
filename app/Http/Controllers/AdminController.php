<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penjadwalan;
use App\Models\Absensi;
use App\Models\Peralatan;
use Barryvdh\DomPDF\Facade\Pdf; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jumlahOperator     = User::where('role', 'operator')->count();
        $jumlahPenjadwalan  = Penjadwalan::count();
        $jumlahPeralatan    = Peralatan::count();

        $absensi = Absensi::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $hadir      = $absensi['hadir'] ?? 0;
        $izin       = $absensi['izin'] ?? 0;
        $sakit      = $absensi['sakit'] ?? 0;
        $tidakHadir = $absensi['tidak_hadir'] ?? 0;

        $operatorData = User::where('role', 'operator')
            ->withCount('penjadwalan')
            ->get();
        $operatorLabels = $operatorData->pluck('nama_user');
        $operatorCounts = $operatorData->pluck('penjadwalan_count');

        $absensiPerTanggal = Absensi::selectRaw('tanggal, status, COUNT(*) as total')
            ->groupBy('tanggal', 'status')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->groupBy('tanggal');

        $tanggalLabels = [];
        $hadirData = [];
        $izinData = [];
        $sakitData = [];
        $tidakHadirData = [];

        foreach ($absensiPerTanggal as $tanggal => $records) {
            $tanggalLabels[] = \Carbon\Carbon::parse($tanggal)->format('d/m');
            $hadirData[]      = $records->firstWhere('status', 'hadir')->total ?? 0;
            $izinData[]       = $records->firstWhere('status', 'izin')->total ?? 0;
            $sakitData[]      = $records->firstWhere('status', 'sakit')->total ?? 0;
            $tidakHadirData[] = $records->firstWhere('status', 'tidak_hadir')->total ?? 0;
        }

        return view('admin.dashboard', compact(
            'jumlahOperator',
            'jumlahPenjadwalan',
            'jumlahPeralatan',
            'hadir',
            'izin',
            'sakit',
            'tidakHadir',
            'operatorLabels',
            'operatorCounts',
            'operatorData',
            'tanggalLabels',
            'hadirData',
            'izinData',
            'sakitData',
            'tidakHadirData'
        ));
    }

    public function absensi()
    {
        $absensi = Absensi::with(['user','penjadwalan'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.absensi.index', compact('absensi'));
    }

    
    public function validateAbsensi($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->update(['validated' => 1]);

        return redirect()->route('admin.absensi.index')->with('success', 'Absensi berhasil divalidasi.');
    }

    
    public function unvalidateAbsensi($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->update(['validated' => 0]);

        return redirect()->route('admin.absensi.index')->with('success', 'Validasi absensi dibatalkan.');
    }

    
    public function destroyAbsensi($id)
    {
        $absen = Absensi::findOrFail($id);
        $absen->delete();

        return redirect()->route('admin.absensi.index')->with('success', 'Absensi berhasil dihapus.');
    }

    
public function laporan(Request $request)
    {
        $query = Penjadwalan::with(['user','absensi']);

        if ($request->start) {
            $query->whereDate('tanggal', '>=', $request->start);
        }
        if ($request->end) {
            $query->whereDate('tanggal', '<=', $request->end);
        }
        if ($request->operator) {
            $query->where('id_user', $request->operator);
        }

        $jadwal = $query->orderBy('tanggal','desc')->get();
        $operators = User::where('role','operator')->get();

        return view('admin.laporan.index', compact('jadwal','operators'));
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
