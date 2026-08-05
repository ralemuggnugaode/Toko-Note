<?php

namespace App\Http\Controllers;

use App\Services\StockRecommendationService;

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

        return view('pages.rekomendasi', [
            'title' => 'Rekomendasi Stok (AI)',
            'hasil' => $hasil,
        ]);
    }
}
