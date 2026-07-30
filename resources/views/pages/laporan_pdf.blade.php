<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-end { text-align: right; }
        .badge { padding: 2px 6px; border-radius: 4px; color: #fff; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; color: #000; }
        .bg-danger { background-color: #dc3545; }
        .bg-primary { background-color: #007bff; }
        .bg-secondary { background-color: #6c757d; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Lengkap</h2>
        <p>Periode: {{ $start }} s/d {{ $end }}</p>
    </div>

    <h3>1. Stok Barang</h3>
    <table>
        <thead>
            <tr>
                <th>Kode</th><th>Nama</th><th>Kategori</th>
                <th class="text-end">Harga Beli</th><th class="text-end">Harga Jual</th>
                <th class="text-end">Stok</th><th class="text-end">Nilai Stok</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stokBarang as $item)
            <tr>
                <td>{{ $item->{'719_kode'} }}</td>
                <td>{{ $item->{'719_nama'} }}</td>
                <td>{{ $item->{'719_kategori'} }}</td>
                <td class="text-end">Rp {{ number_format($item->{'719_harga_beli'}, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($item->{'719_harga_jual'}, 0, ',', '.') }}</td>
                <td class="text-end">{{ $item->{'719_stok_tercatat'} }}</td>
                <td class="text-end">Rp {{ number_format($item->{'719_stok_tercatat'} * $item->{'719_harga_beli'}, 0, ',', '.') }}</td>
                <td>{{ $item->{'719_stok_tercatat'} <= $item->{'719_stok_min'} ? 'Menipis' : 'Aman' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-end">Total Nilai Stok</th>
                <th class="text-end">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <h3>2. Rekap Keuangan</h3>
    <table>
        <tr><td><strong>Total Pemasukan</strong></td><td>Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td></tr>
        <tr><td><strong>Total Pengeluaran</strong></td><td>Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td></tr>
        <tr><td><strong>Selisih</strong></td><td>Rp {{ number_format($selisih, 0, ',', '.') }}</td></tr>
    </table>

    @if($dates->isNotEmpty())
        <table>
            <thead><tr><th>Tanggal</th><th class="text-end">Pemasukan</th><th class="text-end">Pengeluaran</th><th class="text-end">Saldo</th></tr></thead>
            <tbody>
                @foreach ($dates as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                    <td class="text-end">Rp {{ number_format($row['masuk'], 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($row['keluar'], 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3>3. Aktivitas Pengguna</h3>
    <table>
        <thead><tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Model</th><th>ID</th></tr></thead>
        <tbody>
            @forelse ($activities as $log)
            <tr>
                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $log->user->name ?? 'System' }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ class_basename($log->model_type) }}</td>
                <td>{{ $log->model_id ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5">Tidak ada aktivitas</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dicetak pada {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
