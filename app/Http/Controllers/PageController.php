<?php

namespace App\Http\Controllers;

use App\Models\Masuk729;
use App\Models\Pengeluaran_742;
use App\Models\StokBarang_719;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $pemasukanHariIni = Masuk729::whereDate('tanggal',$today)->sum('total') ?? 0;
        $pengeluaranHariIni = Pengeluaran_742::whereDate('tanggal_742',$today)->sum('total_742') ?? 0;
        $totalBarang = StokBarang_719::count();
        $stokMenipis = StokBarang_719::whereColumn('719_stok_tercatat','<=','719_stok_min')->count();
        $dates = collect();
        $omzetData = collect();
        for ($i = 6; $i >= 0 ; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates->push($date->format('d M'));
            $total = Masuk729::whereDate('tanggal',$date)->sum('total') ?? 0;
            $omzetData->push($total);
        }
        return view('pages.index',compact(
            'pemasukanHariIni',
            'pengeluaranHariIni',
            'totalBarang',
            'stokMenipis',
            'dates',
            'omzetData'
        ));
    }
}
