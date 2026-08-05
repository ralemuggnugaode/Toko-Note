@extends('pages.partials.main')
@section('page')
                <div class="container-fluid py-2">

                    {{-- ================================================= --}}
                    {{-- HEADER SSRS (icon & warna diperbaiki)            --}}
                    {{-- ================================================= --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-gradient-primary shadow-lg mb-4 overflow-hidden">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-white shadow rounded-circle"
                                                style="width: 4rem; height: 4rem; position: relative;">
                                                <i class="fa fa-cogs fa-2x text-primary"
                                                    style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0; line-height: 1;">
                                                </i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h5 class="text-white mb-1 font-weight-bolder">
                                                Smart Stock Reorder System <span
                                                    class="badge bg-white text-primary ms-2 text-xs">AI-Powered</span>
                                            </h5>
                                            <p class="text-white text-sm mb-0 opacity-8">
                                                Rekomendasi jumlah pembelian otomatis berbasis <strong>Fuzzy Logic Sugeno</strong>.
                                                Dihitung real-time dari data <strong>Barang Masuk</strong>, <strong>Barang Keluar</strong>,
                                                dan <strong>Stok</strong> terkini.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================================================= --}}
                    {{-- KNOWLEDGE BASE (Basis Pengetahuan)                --}}
                    {{-- ================================================= --}}
                    <div class="row mb-3 no-print">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Knowledge Base (Basis Pengetahuan)</h6>
                                </div>
                                <div class="card-body p-3">
                                    <ul class="text-sm mb-0">
                                        <li>Jika penjualan sedang tinggi dan stok menipis, maka prioritas pembelian harus segera dilakukan.</li>
                                        <li>Jika trend penjualan menurun, sebaiknya tidak membeli barang dalam jumlah besar untuk menghindari overstock.</li>
                                        <li>Jika pasokan barang dari supplier lambat, maka rekomendasi pembelian harus memperhitungkan waktu tunggu (lead time).</li>
                                        <li>Jika stok masih berlebih meskipun penjualan tinggi, pembelian bisa ditunda hingga stok menipis.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

            {{-- ================================================= --}}
            {{-- RULE BASE (16 Aturan) - Toggle Native HTML5 --}}
            {{-- ================================================= --}}
            <div class="row mb-3 no-print">
                <div class="col-12">
                    <div class="card">
                        <details class="card-details">
                            <summary class="card-header d-flex justify-content-between align-items-center flex-wrap list-unstyled">
                                <h6 class="mb-0">
                                    <i class="fa-solid fa-gear me-1"></i> Rule Base (16 Aturan Sugeno)
                                    <span class="badge bg-gradient-primary ms-2">IF-THEN</span>
                                </h6>
                                <span class="btn btn-sm bg-gradient-primary mb-0">
                                    <i class="fa-regular fa-eye"></i> Tampilkan Aturan
                                </span>
                            </summary>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                                    <table class="table align-items-center mb-0">
                                        <thead class="bg-gradient-primary text-white">
                                            <tr>
                                                <th class="text-center ps-3" style="width: 5%;">#</th>
                                                <th style="width: 18%;">Penjualan</th>
                                                <th style="width: 15%;">Stok</th>
                                                <th style="width: 15%;">Trend</th>
                                                <th style="width: 17%;">Pasokan</th>
                                                <th style="width: 30%;">Rekomendasi (z)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rules as $rule)
                                                <tr>
                                                    <td class="text-center fw-bold">{{ $rule['no'] }}</td>
                                                    <td><span class="badge bg-gradient-info">{{ $rule['penjualan'] }}</span></td>
                                                    <td><span class="badge bg-gradient-warning">{{ $rule['stok'] }}</span></td>
                                                    <td><span class="badge bg-gradient-secondary">{{ $rule['trend'] }}</span></td>
                                                    <td><span class="badge bg-gradient-success">{{ $rule['pasokan'] }}</span></td>
                                                    <td><code class="badge bg-light text-dark">{{ $rule['z'] }}</code></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer bg-gray-100 text-xs py-2 px-3 d-flex justify-content-between flex-wrap">
                                    <span><i class="fa-regular fa-circle-info me-1"></i> Seluruh basis aturan yang digunakan oleh
                                        mesin inferensi.</span>
                                    <span><i class="fa-regular fa-clock me-1"></i> Total 16 aturan</span>
                                </div>
                            </div>
                        </details>
                    </div>
                </div>
            </div>

                    {{-- =============================== --}}
                    {{-- 1. Ringkasan Statistik (Summary) --}}
                    {{-- =============================== --}}
                    <div class="row mb-4">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="text-sm mb-0 text-secondary font-weight-bold">Total Barang</p>
                                            <h5 class="font-weight-bolder mb-0">{{ $hasil->count() }}</h5>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                                <i class="fa-solid fa-box text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="text-sm mb-0 text-secondary font-weight-bold">Rekomendasi Beli</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                {{ $hasil->filter(fn($r) => $r['rekomendasi'] > 0)->count() }}
                                            </h5>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                                <i class="fa-solid fa-cart-plus text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="text-sm mb-0 text-secondary font-weight-bold">Total Unit Direkomendasikan</p>
                                            <h5 class="font-weight-bolder mb-0">{{ $hasil->sum('rekomendasi') }}</h5>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                                <i class="fa-solid fa-arrow-trend-up text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <p class="text-sm mb-0 text-secondary font-weight-bold">Stok Kritis</p>
                                            <h5 class="font-weight-bolder mb-0">
                                                {{ $hasil->filter(fn($r) => $r['metrics']['stok'] <= $r['metrics']['stok_min'])->count() }}
                                            </h5>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                                <i class="fa-solid fa-triangle-exclamation text-lg opacity-10"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================================= --}}
                    {{-- 2. Filter dan Pencarian           --}}
                    {{-- ================================= --}}
                    <div class="row mb-3 no-print">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-2">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-4">
                                            <input type="text" id="searchBarang" class="form-control form-control-sm" placeholder="🔍 Cari nama / kode barang...">
                                        </div>
                                        <div class="col-md-3">
                                            <select id="filterKategori" class="form-select form-select-sm">
                                                <option value="">Semua Kategori</option>
                                                @php
    $kategoriList = $hasil->pluck('barang')->pluck('719_kategori')->unique()->sort()->values();
                                                @endphp
                                                @foreach ($kategoriList as $kat)
                                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select id="filterRekomendasi" class="form-select form-select-sm">
                                                <option value="">Semua Status</option>
                                                <option value="1">Perlu Beli</option>
                                                <option value="0">Tidak Perlu</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 text-md-end">
                                            <button id="resetFilter" class="btn btn-sm bg-gradient-secondary w-100 mb-0">
                                                <i class="fa-solid fa-rotate-left"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================================ --}}
                    {{-- 3. Tabel Rekomendasi (Desktop)   --}}
                    {{-- ================================ --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center flex-wrap">
                                    <h6>Rekomendasi per Barang</h6>
                                    <div>
                                        <a href="{{ route('page.rekomendasi.export', ['type' => 'pdf']) }}" class="btn bg-gradient-danger btn-sm mb-0">
                                            <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                        </a>
                                        <button onclick="window.print()" class="btn bg-gradient-secondary btn-sm mb-0">
                                            <i class="fa-solid fa-print me-1"></i> Cetak
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">

                                    {{-- Tabel untuk layar medium ke atas --}}
                                    <div class="table-responsive p-0 d-none d-md-block">
                                        <table class="table align-items-center mb-0" id="tabelRekomendasi">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Barang</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Stok</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end d-none d-lg-table-cell">Penjualan Bulan Ini</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end d-none d-lg-table-cell">Trend</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-xl-table-cell">Label Fuzzy</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Rekomendasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($hasil as $r)
                                                    @php
        $b = $r['barang'];
        $m = $r['metrics'];
        $l = $r['label'];
        $trendIcon = $m['trend_persen'] > 0 ? 'fa-arrow-up text-success' : ($m['trend_persen'] < 0 ? 'fa-arrow-down text-danger' : 'fa-minus text-secondary');
        $pctStok = min(100, ($m['stok'] / max($m['stok_min'], 1)) * 100);
        $barColor = $pctStok <= 30 ? 'danger' : ($pctStok <= 70 ? 'warning' : 'success');
                                                    @endphp
                                                    <tr class="{{ $r['rekomendasi'] > 0 ? 'table-success' : '' }}"
                                                        data-kategori="{{ $b->{'719_kategori'} }}"
                                                        data-status="{{ $r['rekomendasi'] > 0 ? '1' : '0' }}"
                                                        data-nama="{{ strtolower($b->{'719_nama'}) }}"
                                                        data-kode="{{ strtolower($b->{'719_kode'}) }}">
                                                        <td>
                                                            <div class="d-flex px-2 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm">{{ $b->{'719_nama'} }}</h6>
                                                                    <p class="text-xs text-secondary mb-0">{{ $b->{'719_kode'} }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="d-flex flex-column align-items-end">
                                                                <div class="progress w-75" style="height: 6px;">
                                                                    <div class="progress-bar bg-{{ $barColor }}" style="width: {{ $pctStok }}%;"></div>
                                                                </div>
                                                                <span class="text-xs">{{ $m['stok'] }} / min {{ $m['stok_min'] }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-sm text-end d-none d-lg-table-cell">{{ $m['penjualan_bulan_ini'] }} unit</td>
                                                        <td class="text-sm text-end d-none d-lg-table-cell">
                                                            <i class="fa-solid {{ $trendIcon }} me-1"></i>{{ $m['trend_persen'] }}%
                                                        </td>
                                                        <td class="text-xs d-none d-xl-table-cell">
                                                            <span class="badge badge-sm bg-gradient-info">{{ $l['penjualan'] }}</span>
                                                            <span class="badge badge-sm bg-gradient-warning">{{ $l['stok'] }}</span>
                                                            <span class="badge badge-sm bg-gradient-secondary">{{ $l['trend'] }}</span>
                                                            <span class="badge badge-sm bg-gradient-dark">{{ $l['pasokan'] }}</span>
                                                        </td>
                                                        <td class="text-end">
                                                            @if($r['rekomendasi'] > 0)
                                                                <span class="badge badge-sm bg-gradient-success">Beli {{ $r['rekomendasi'] }} unit</span>
                                                            @else
                                                                <span class="badge badge-sm bg-gradient-secondary">Aman</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-secondary text-xs">Belum ada data stok barang</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Card view untuk mobile --}}
                                    <div class="d-block d-md-none px-2" id="cardContainer">
                                        @foreach ($hasil as $r)
                                            @php
        $b = $r['barang'];
        $m = $r['metrics'];
        $trendIcon = $m['trend_persen'] > 0 ? 'fa-arrow-up text-success' : ($m['trend_persen'] < 0 ? 'fa-arrow-down text-danger' : 'fa-minus text-secondary');
                                            @endphp
                                            <div class="card mb-2 rekomendasi-card"
                                                 data-kategori="{{ $b->{'719_kategori'} }}"
                                                 data-status="{{ $r['rekomendasi'] > 0 ? '1' : '0' }}"
                                                 data-nama="{{ strtolower($b->{'719_nama'}) }}"
                                                 data-kode="{{ strtolower($b->{'719_kode'}) }}">
                                                <div class="card-body p-2">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong>{{ $b->{'719_nama'} }}</strong>
                                                            <span class="text-secondary text-xs d-block">{{ $b->{'719_kode'} }}</span>
                                                        </div>
                                                        <span class="badge bg-{{ $r['rekomendasi'] > 0 ? 'success' : 'secondary' }}">
                                                            {{ $r['rekomendasi'] > 0 ? 'Beli ' . $r['rekomendasi'] . ' unit' : 'Aman' }}
                                                        </span>
                                                    </div>
                                                    <div class="row text-xs mt-2">
                                                        <div class="col-6">Stok: {{ $m['stok'] }} / min {{ $m['stok_min'] }}</div>
                                                        <div class="col-6">Penjualan bulan ini: {{ $m['penjualan_bulan_ini'] }}</div>
                                                        <div class="col-12 mt-1">
                                                            Trend: <i class="fa-solid {{ $trendIcon }}"></i> {{ $m['trend_persen'] }}%
                                                        </div>
                                                        <div class="col-12 mt-1">
                                                            @foreach (['penjualan', 'stok', 'trend', 'pasokan'] as $label)
                                                                <span class="badge badge-sm bg-gradient-secondary me-1">{{ $r['label'][$label] }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($hasil->isEmpty())
                                            <div class="text-center text-secondary text-xs py-3">Belum ada data</div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ==================================== --}}
                    {{-- 4. Detail Perhitungan (Accordion)    --}}
                    {{-- ==================================== --}}
                    <div class="row mt-4" no-print>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                                    <h6>Detail Perhitungan <small class="text-secondary text-xs">(Rule yang terpicu)</small></h6>
                                    <span class="badge bg-secondary bg-gradient">{{ $hasil->filter(fn($r) => count($r['rules_fired']) > 0)->count() }} barang aktif</span>
                                </div>
                                <div class="card-body">
                                    @if($hasil->isNotEmpty())
                                        <div class="accordion" id="accordionRules">
                                            @foreach ($hasil as $i => $r)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rule-{{ $i }}">
                                                            {{ $r['barang']->{'719_nama'} }}
                                                            <span class="badge bg-info ms-2">{{ count($r['rules_fired']) }} rule</span>
                                                            <span class="badge bg-{{ $r['rekomendasi'] > 0 ? 'success' : 'secondary' }} ms-1">
                                                                z = {{ $r['rekomendasi'] }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="rule-{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#accordionRules">
                                                        <div class="accordion-body">
                                                            @if(count($r['rules_fired']))
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-bordered align-middle">
                                                                        <thead class="bg-light">
                                                                            <tr>
                                                                                <th>Rule #</th>
                                                                                <th>Kondisi</th>
                                                                                <th class="text-end">Alpha (predikat)</th>
                                                                                <th class="text-end">z (output)</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($r['rules_fired'] as $f)
                                                                                <tr>
                                                                                    <td>{{ $f['no'] }}</td>
                                                                                    <td class="text-xs">
                                                                                        Penjualan {{ $f['rule']['penjualan'] }},
                                                                                        Stok {{ $f['rule']['stok'] }},
                                                                                        Trend {{ $f['rule']['trend'] }},
                                                                                        Pasokan {{ $f['rule']['pasokan'] }}
                                                                                    </td>
                                                                                    <td class="text-end">{{ round($f['alpha'], 2) }}</td>
                                                                                    <td class="text-end">{{ round($f['z'], 1) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                {{-- === TAMBAHAN: Perhitungan Weighted Average === --}}
                                                                @php
                $totalAlphaZ = 0;
                $totalAlpha = 0;
                foreach ($r['rules_fired'] as $f) {
                    $totalAlphaZ += $f['alpha'] * $f['z'];
                    $totalAlpha += $f['alpha'];
                }
                $finalZ = $totalAlpha > 0 ? $totalAlphaZ / $totalAlpha : 0;
                                                                @endphp
                                                                <div class="mt-3 p-2 bg-light border rounded">
                                                                    <strong>Weighted Average (Defuzzifikasi):</strong>
                                                                    <br>
                                                                    Σ (alpha · z) = <code>{{ round($totalAlphaZ, 2) }}</code> &nbsp;|&nbsp;
                                                                    Σ alpha = <code>{{ round($totalAlpha, 2) }}</code> &nbsp;|&nbsp;
                                                                    <span class="badge bg-primary">z = {{ round($finalZ, 2) }}</span>
                                                                    &nbsp;→ dibulatkan menjadi <strong>{{ $r['rekomendasi'] }} unit</strong>
                                                                </div>
                                                            @else
                                                                <p class="text-secondary text-xs mb-0">
                                                                    <i class="fa-regular fa-circle-info me-1"></i>
                                                                    Tidak ada rule dari 16 aturan yang terpicu — sistem memakai rumus fallback (kebutuhan minimum).
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center text-secondary text-xs py-3">Belum ada data untuk ditampilkan</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- =============================== --}}
                {{-- 5. Style untuk Cetak & Perbaikan --}}
                {{-- =============================== --}}
                <style>
                    @media print {
                        .navbar, .sidenav, .footer, .btn, #resetFilter,
                        .card-header .btn, .card-header button,
                        .card-header a, .no-print, .bg-gradient-primary .badge,
                        .filter-section, #searchBarang, #filterKategori,
                        #filterRekomendasi, .card-header .btn-sm,
                        .accordion-button::after {
                            display: none !important;
                        }
                        body, .container-fluid {
                            margin: 0 !important;
                            padding: 0 !important;
                            width: 100% !important;
                        }
                        .bg-gradient-primary {
                            background: #fff !important;
                            color: #000 !important;
                            box-shadow: none !important;
                        }
                        .text-white {
                            color: #000 !important;
                        }
                        .card {
                            box-shadow: none !important;
                            border: 1px solid #ddd;
                        }
                        @page {
                            size: auto;
                            margin: 10mm;
                        }
                        div:empty { display: none; }
                        .accordion-collapse {
                            display: block !important;
                            height: auto !important;
                        }
                        .accordion-button {
                            cursor: default;
                            background: none !important;
                            box-shadow: none !important;
                        }
                    }
                </style>

                {{-- =============================== --}}
                {{-- 6. Script untuk Filter & Interaksi --}}
                {{-- =============================== --}}
                @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {

                        const searchInput = document.getElementById('searchBarang');
                        const filterKategori = document.getElementById('filterKategori');
                        const filterRekomendasi = document.getElementById('filterRekomendasi');
                        const resetBtn = document.getElementById('resetFilter');

                        const rows = document.querySelectorAll('#tabelRekomendasi tbody tr');
                        const cards = document.querySelectorAll('.rekomendasi-card');

                        function filterData() {
                            const keyword = searchInput.value.toLowerCase().trim();
                            const kategori = filterKategori.value;
                            const status = filterRekomendasi.value;

                            rows.forEach(row => {
                                const nama = row.dataset.nama || '';
                                const kode = row.dataset.kode || '';
                                const rowKategori = row.dataset.kategori || '';
                                const rowStatus = row.dataset.status || '';

                                let show = true;
                                if (keyword && !nama.includes(keyword) && !kode.includes(keyword)) show = false;
                                if (kategori && rowKategori !== kategori) show = false;
                                if (status !== '' && rowStatus !== status) show = false;

                                row.style.display = show ? '' : 'none';
                            });

                            cards.forEach(card => {
                                const nama = card.dataset.nama || '';
                                const kode = card.dataset.kode || '';
                                const cardKategori = card.dataset.kategori || '';
                                const cardStatus = card.dataset.status || '';

                                let show = true;
                                if (keyword && !nama.includes(keyword) && !kode.includes(keyword)) show = false;
                                if (kategori && cardKategori !== kategori) show = false;
                                if (status !== '' && cardStatus !== status) show = false;

                                card.style.display = show ? '' : 'none';
                            });
                        }

                        searchInput.addEventListener('keyup', filterData);
                        filterKategori.addEventListener('change', filterData);
                        filterRekomendasi.addEventListener('change', filterData);

                        resetBtn.addEventListener('click', function() {
                            searchInput.value = '';
                            filterKategori.value = '';
                            filterRekomendasi.value = '';
                            filterData();
                        });

                        filterData();

                    });
                </script>
                @endpush

@endsection
