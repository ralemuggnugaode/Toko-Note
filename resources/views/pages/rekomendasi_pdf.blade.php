<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        /* Reset dasar */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
            background: #fff;
        }

        /* HEADER */
        .header-table {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }
        .header-table td {
            vertical-align: middle;
            padding: 0;
        }
        .logo-cell {
            width: 70px;
        }
        .logo-box {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #2563eb, #1e40af);
            border-radius: 50%;
            text-align: center;
            font-size: 28px;
            color: #fff;
            line-height: 60px;
            font-weight: bold;
            margin-right: 15px;
        }
        .title-main {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 2px;
        }
        .title-sub {
            font-size: 11px;
            color: #475569;
        }
        .title-badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 9px;
            margin-left: 8px;
            vertical-align: middle;
        }
        .date-cell {
            text-align: right;
            font-size: 10px;
            color: #64748b;
        }

        /* RINGKASAN */
        .summary-table {
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 10px;
        }
        .summary-table td {
            width: 25%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            text-align: center;
            border-radius: 8px; /* tidak selalu didukung, tapi aman */
        }
        .summary-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
            display: block;
        }
        .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            display: block;
        }
        .summary-icon {
            font-size: 14px;
            display: inline-block;
            margin-right: 3px;
        }

        /* TABEL UTAMA */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .main-table thead th {
            background-color: #2563eb;
            color: #fff;
            padding: 8px 6px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #1d4ed8;
            text-transform: uppercase;
        }
        .main-table tbody td {
            padding: 7px 6px;
            border: 1px solid #cbd5e1;
            font-size: 10px;
            vertical-align: middle;
        }
        .main-table tbody tr:nth-child(even) {
            background-color: #f1f5f9;
        }
        .main-table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 9px;
            border-radius: 12px;
            background-color: #e2e8f0;
            color: #334155;
            margin: 1px;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
            font-weight: bold;
            padding: 3px 8px;
        }
        .badge-safe {
            background-color: #e0e7ff;
            color: #3730a3;
            padding: 3px 8px;
        }

        .stock-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 4px;
            vertical-align: middle;
        }
        .bg-red { background-color: #ef4444; }
        .bg-yellow { background-color: #eab308; }
        .bg-green { background-color: #22c55e; }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <div class="logo-box">⚙️</div>
            </td>
            <td>
                <div class="title-main">
                    Smart Stock Reorder System
                    <span class="title-badge">AI-Powered</span>
                </div>
                <div class="title-sub">
                    Laporan Rekomendasi Pembelian Otomatis &bull; Fuzzy Logic Sugeno
                </div>
            </td>
            <td class="date-cell">
                Dicetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}<br>
                Oleh: {{ auth()->user()->name ?? 'Sistem' }}
            </td>
        </tr>
    </table>

    <!-- RINGKASAN -->
    @php
        $totalBarang = $hasil->count();
        $rekomendasiBeli = $hasil->filter(fn($r) => $r['rekomendasi'] > 0)->count();
        $totalUnit = $hasil->sum('rekomendasi');
        $stokKritis = $hasil->filter(fn($r) => $r['metrics']['stok'] <= $r['metrics']['stok_min'])->count();
    @endphp
    <table class="summary-table">
        <tr>
            <td>
                <span class="summary-label"><span class="summary-icon">📦</span> Total Barang</span>
                <span class="summary-value">{{ $totalBarang }}</span>
            </td>
            <td>
                <span class="summary-label"><span class="summary-icon">🛒</span> Perlu Dibeli</span>
                <span class="summary-value">{{ $rekomendasiBeli }}</span>
            </td>
            <td>
                <span class="summary-label"><span class="summary-icon">📊</span> Total Unit</span>
                <span class="summary-value">{{ $totalUnit }}</span>
            </td>
            <td>
                <span class="summary-label"><span class="summary-icon">⚠️</span> Stok Kritis</span>
                <span class="summary-value">{{ $stokKritis }}</span>
            </td>
        </tr>
    </table>

    <!-- TABEL DATA -->
    <table class="main-table">
        <thead>
            <tr>
                <th width="25%">Nama Barang / Kode</th>
                <th width="12%">Stok</th>
                <th width="13%">Penjualan</th>
                <th width="10%">Trend</th>
                <th width="25%">Label Fuzzy</th>
                <th width="15%">Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($hasil as $r)
                @php
                    $b = $r['barang'];
                    $m = $r['metrics'];
                    $l = $r['label'];
                    $pctStok = min(100, ($m['stok'] / max($m['stok_min'], 1)) * 100);
                    $stokColor = $pctStok <= 30 ? 'bg-red' : ($pctStok <= 70 ? 'bg-yellow' : 'bg-green');
                    $trendPercent = $m['trend_persen'];
                @endphp
                <tr>
                    <td>
                        <strong>{{ $b->{'719_nama'} }}</strong><br>
                        <span style="font-size:9px; color:#475569;">{{ $b->{'719_kode'} }}</span>
                    </td>
                    <td class="text-center">
                        <span class="stock-indicator {{ $stokColor }}"></span>
                        {{ $m['stok'] }} / min {{ $m['stok_min'] }}
                    </td>
                    <td class="text-center">{{ $m['penjualan_bulan_ini'] }} unit</td>
                    <td class="text-center">
                        @if($trendPercent > 0)
                            <span style="color:#22c55e;">↑ {{ $trendPercent }}%</span>
                        @elseif($trendPercent < 0)
                            <span style="color:#ef4444;">↓ {{ abs($trendPercent) }}%</span>
                        @else
                            <span style="color:#64748b;">– 0%</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge">{{ $l['penjualan'] }}</span>
                        <span class="badge">{{ $l['stok'] }}</span>
                        <span class="badge">{{ $l['trend'] }}</span>
                        <span class="badge">{{ $l['pasokan'] }}</span>
                    </td>
                    <td class="text-center">
                        @if($r['rekomendasi'] > 0)
                            <span class="badge badge-success">Beli {{ $r['rekomendasi'] }} unit</span>
                        @else
                            <span class="badge badge-safe">Aman</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data rekomendasi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Laporan ini dibuat otomatis oleh Smart Stock Reorder System (SSRS) &bull; Data bersumber dari Barang Masuk, Barang Keluar, dan Stok terkini.
    </div>

</body>
</html>
