@extends('pages.partials.main')
@section('page')
<div class="container-fluid py-2">

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Smart Stock Reorder System (SSRS)</h5>
                    <p class="text-secondary text-sm mb-0">
                        Rekomendasi jumlah pembelian (reorder) otomatis berbasis <strong>Fuzzy Logic Sugeno</strong>,
                        dihitung dari data Catatan Masuk, Catatan Keluar, dan Stok Barang.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Rekomendasi per Barang</h6>
                    <span class="badge bg-secondary bg-gradient">{{ $hasil->count() }} item</span>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Barang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Stok</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end d-none d-md-table-cell">Penjualan Bulan Ini</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end d-none d-lg-table-cell">Trend</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-lg-table-cell">Label Fuzzy</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Rekomendasi Beli</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hasil as $r)
                                    @php
                                        $b = $r['barang'];
                                        $m = $r['metrics'];
                                        $l = $r['label'];
                                        $trendIcon = $m['trend_persen'] > 0 ? 'fa-arrow-up text-success' : ($m['trend_persen'] < 0 ? 'fa-arrow-down text-danger' : 'fa-minus text-secondary');
                                    @endphp
                                    <tr>
                                        <td class="text-sm">
                                            <strong>{{ $b->{'719_nama'} }}</strong><br>
                                            <span class="text-secondary text-xs">{{ $b->{'719_kode'} }}</span>
                                        </td>
                                        <td class="text-sm text-end">{{ $m['stok'] }} <span class="text-secondary text-xs">/ min {{ $m['stok_min'] }}</span></td>
                                        <td class="text-sm text-end d-none d-md-table-cell">{{ $m['penjualan_bulan_ini'] }} unit</td>
                                        <td class="text-sm text-end d-none d-lg-table-cell">
                                            <i class="fa-solid {{ $trendIcon }} me-1"></i>{{ $m['trend_persen'] }}%
                                        </td>
                                        <td class="text-xs d-none d-lg-table-cell">
                                            <span class="badge badge-sm bg-gradient-info">{{ $l['penjualan'] }}</span>
                                            <span class="badge badge-sm bg-gradient-warning">{{ $l['stok'] }}</span>
                                            <span class="badge badge-sm bg-gradient-secondary">{{ $l['trend'] }}</span>
                                            <span class="badge badge-sm bg-gradient-dark">{{ $l['pasokan'] }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if($r['rekomendasi'] > 0)
                                                <span class="badge badge-sm bg-gradient-success">Beli {{ $r['rekomendasi'] }} unit</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-secondary">Tidak Perlu Beli</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-secondary text-xs">Belum ada data stok barang</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Detail Perhitungan (Rule yang Terpicu)</h6>
                    <p class="text-secondary text-xs mb-0">Klik nama barang untuk lihat rule mana yang aktif dan bobotnya (untuk verifikasi/lampiran laporan).</p>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionRules">
                        @foreach ($hasil as $i => $r)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#rule-{{ $i }}">
                                        {{ $r['barang']->{'719_nama'} }} — z = {{ $r['rekomendasi'] }}
                                    </button>
                                </h2>
                                <div id="rule-{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#accordionRules">
                                    <div class="accordion-body">
                                        @if(count($r['rules_fired']))
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Rule #</th>
                                                        <th>Kondisi</th>
                                                        <th class="text-end">Alpha (predikat)</th>
                                                        <th class="text-end">z (output rule)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($r['rules_fired'] as $f)
                                                        <tr>
                                                            <td>{{ $f['no'] }}</td>
                                                            <td class="text-xs">
                                                                Penjualan {{ $f['rule']['penjualan'] }}, Stok {{ $f['rule']['stok'] }},
                                                                Trend {{ $f['rule']['trend'] }}, Pasokan {{ $f['rule']['pasokan'] }}
                                                            </td>
                                                            <td class="text-end">{{ round($f['alpha'], 2) }}</td>
                                                            <td class="text-end">{{ round($f['z'], 1) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-secondary text-xs mb-0">Tidak ada rule dari 16 aturan yang terpicu — sistem memakai rumus fallback (kebutuhan minimum).</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
