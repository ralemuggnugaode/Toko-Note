<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Masuk729;
use App\Models\Pengeluaran_742;
use App\Models\StokBarang_719;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end   = $request->input('end_date', now()->toDateString());

        $stokBarang = StokBarang_719::all();
        $totalNilaiStok = $stokBarang->sum(function ($item) {
            return $item->{'719_stok_tercatat'} * $item->{'719_harga_beli'};
        });

        $totalMasuk = Masuk729::whereBetween('tanggal', [$start, $end])->sum('total');
        $totalKeluar = Pengeluaran_742::whereBetween('tanggal_742', [$start, $end])->sum('total_742');
        $selisih = $totalMasuk - $totalKeluar;

        $dailyReport = Masuk729::select(DB::raw('tanggal, SUM(total) as total_masuk'))
            ->whereBetween('tanggal', [$start, $end])
            ->groupBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $dailyKeluar = Pengeluaran_742::select(DB::raw('tanggal_742 as tanggal, SUM(total_742) as total_keluar'))
            ->whereBetween('tanggal_742', [$start, $end])
            ->groupBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        $dates = collect();
        $period = \Carbon\CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $dates->push([
                'date' => $d,
                'masuk' => $dailyReport->get($d)->total_masuk ?? 0,
                'keluar' => $dailyKeluar->get($d)->total_keluar ?? 0,
                'saldo' => ($dailyReport->get($d)->total_masuk ?? 0) - ($dailyKeluar->get($d)->total_keluar ?? 0),
            ]);
        }

        $activities = ActivityLog::with('user')
            ->whereBetween('created_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->latest()
            ->get();

        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('pages.laporan_pdf', compact(
                'stokBarang', 'totalNilaiStok',
                'totalMasuk', 'totalKeluar', 'selisih',
                'dates', 'activities', 'start', 'end'
            ));
            return $pdf->download('laporan-' . now()->format('Ymd_His') . '.pdf');
        }

        return view('pages.laporan', compact(
            'stokBarang', 'totalNilaiStok',
            'totalMasuk', 'totalKeluar', 'selisih',
            'dates', 'activities', 'start', 'end'
        ));
    }
}
