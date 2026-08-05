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

        $rules = $this->service->getRulesForDisplay();

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
