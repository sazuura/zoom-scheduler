<?php

namespace App\Exports;

use App\Models\Penjadwalan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Penjadwalan::with(['user','absensi']);

        if ($this->request->start) $query->whereDate('tanggal','>=',$this->request->start);
        if ($this->request->end) $query->whereDate('tanggal','<=',$this->request->end);
        if ($this->request->operator) $query->where('id_user',$this->request->operator);

        $jadwal = $query->orderBy('tanggal','desc')->get();

        return $jadwal->map(function($j){
            $absen = $j->absensi->first();
            return [
                'Tanggal' => $j->tanggal,
                'Judul Kegiatan' => $j->judul_kegiatan,
                'Operator' => $j->user->nama_user ?? '-',
                'Waktu' => $j->waktu_mulai.' - '.$j->waktu_selesai,
                'Status' => $absen ? ucfirst($absen->status) : 'Belum Absen',
                'Validasi' => $absen ? ($absen->validated ? 'Valid' : 'Menunggu') : '-'
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal','Judul Kegiatan','Operator','Waktu','Status','Validasi'];
    }
}
