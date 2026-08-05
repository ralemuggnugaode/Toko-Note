<?php

namespace App\Http\Controllers;

use App\Services\StockRecommendationService;
use Barryvdh\DomPDF\Facade\Pdf;

class RekomendasiController extends Controller
{
    protected StockRecommendationService $service;

    public function __construct(StockRecommendationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $hasil = $this->service->recommendAll();

        // === 16 ATURAN (Rule Base) sesuai UAS ===
        $rules = [
            ['no' => 1,  'penjualan' => 'RENDAH', 'stok' => 'RENDAH',     'trend' => 'TURUN',   'pasokan' => 'LAMBAT',  'z' => '(Penjualan * 0.5) + (Stok Min * 0.5)'],
            ['no' => 2,  'penjualan' => 'RENDAH', 'stok' => 'RENDAH',     'trend' => 'TURUN',   'pasokan' => 'NORMAL', 'z' => '(Penjualan * 0.6) + (Stok Min * 0.4)'],
            ['no' => 3,  'penjualan' => 'RENDAH', 'stok' => 'RENDAH',     'trend' => 'NAIK',    'pasokan' => 'LAMBAT',  'z' => '(Penjualan * 1.2) - Stok'],
            ['no' => 4,  'penjualan' => 'RENDAH', 'stok' => 'RENDAH',     'trend' => 'NAIK',    'pasokan' => 'NORMAL', 'z' => '(Penjualan * 1.1) - (Stok * 0.5)'],
            ['no' => 5,  'penjualan' => 'RENDAH', 'stok' => 'AMAN',       'trend' => 'TURUN',   'pasokan' => 'CEPAT',  'z' => '0 (Jangan Beli)'],
            ['no' => 6,  'penjualan' => 'RENDAH', 'stok' => 'AMAN',       'trend' => 'STABIL',  'pasokan' => 'NORMAL', 'z' => 'Stok * 0.1 (Beli sedikit)'],
            ['no' => 7,  'penjualan' => 'SEDANG', 'stok' => 'RENDAH',     'trend' => 'NAIK',    'pasokan' => 'LAMBAT',  'z' => '(Penjualan * 1.5) - Stok'],
            ['no' => 8,  'penjualan' => 'SEDANG', 'stok' => 'RENDAH',     'trend' => 'NAIK',    'pasokan' => 'NORMAL', 'z' => '(Penjualan * 1.3) - Stok'],
            ['no' => 9,  'penjualan' => 'SEDANG', 'stok' => 'AMAN',       'trend' => 'STABIL',  'pasokan' => 'NORMAL', 'z' => '(Penjualan * 0.8) - Stok'],
            ['no' => 10, 'penjualan' => 'SEDANG', 'stok' => 'BERLEBIH',   'trend' => 'TURUN',   'pasokan' => 'CEPAT',  'z' => '0 (Jangan Beli)'],
            ['no' => 11, 'penjualan' => 'TINGGI', 'stok' => 'RENDAH',     'trend' => 'NAIK',    'pasokan' => 'LAMBAT',  'z' => '(Penjualan * 2) - Stok'],
            ['no' => 12, 'penjualan' => 'TINGGI', 'stok' => 'RENDAH',     'trend' => 'NAIK',    'pasokan' => 'NORMAL', 'z' => '(Penjualan * 1.8) - Stok'],
            ['no' => 13, 'penjualan' => 'TINGGI', 'stok' => 'AMAN',       'trend' => 'NAIK',    'pasokan' => 'NORMAL', 'z' => '(Penjualan * 1.2) - Stok'],
            ['no' => 14, 'penjualan' => 'TINGGI', 'stok' => 'AMAN',       'trend' => 'STABIL',  'pasokan' => 'CEPAT',  'z' => '(Penjualan * 0.9) - Stok'],
            ['no' => 15, 'penjualan' => 'TINGGI', 'stok' => 'BERLEBIH',   'trend' => 'TURUN',   'pasokan' => 'LAMBAT',  'z' => '(Stok * 0.1)'],
            ['no' => 16, 'penjualan' => 'TINGGI', 'stok' => 'BERLEBIH',   'trend' => 'STABIL',  'pasokan' => 'NORMAL', 'z' => '0 (Jangan Beli, fokus jual stok lama)'],
        ];

        return view('pages.rekomendasi', [
            'title' => 'Rekomendasi Stok (AI)',
            'hasil' => $hasil,
            'rules' => $rules,   
        ]);
    }

    public function export($type)
    {
        if ($type !== 'pdf') {
            abort(404);
        }

        $hasil = $this->service->recommendAll();

        $pdf = PDF::loadView('pages.rekomendasi_pdf', [
            'hasil' => $hasil,
            'title' => 'Rekomendasi Stok (AI)',
        ]);

        return $pdf->download('rekomendasi_stok.pdf');
    }
}
