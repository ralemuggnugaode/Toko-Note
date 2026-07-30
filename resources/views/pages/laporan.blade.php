@extends('pages.partials.main')
@section('page')
<div class="container-fluid py-2">
    <!-- ========== FILTER ========== -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Laporan Lengkap</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('page.report.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label text-secondary text-xs">Tanggal Awal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $start }}">
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <label class="form-label text-secondary text-xs">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $end }}">
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <button type="submit" class="btn bg-gradient-primary w-100 mb-0">
                                <i class="fa-solid fa-filter me-1"></i> Filter
                            </button>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <a href="{{ route('page.report.index', ['export' => 'pdf', 'start_date' => $start, 'end_date' => $end]) }}"
                               class="btn bg-gradient-danger w-100 mb-0">
                                <i class="fa-solid fa-file-pdf me-1"></i> PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== STOK BARANG ========== -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Stok Barang</h6>
                    <span class="badge bg-secondary bg-gradient">{{ $stokBarang->count() }} item</span>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-md-table-cell">Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end d-none d-md-table-cell">Harga Beli</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Harga Jual</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Stok</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end d-none d-lg-table-cell">Nilai Stok</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stokBarang as $item)
                                    <tr>
                                        <td class="text-sm">{{ $item->{'719_kode'} }}</td>
                                        <td class="text-sm">{{ $item->{'719_nama'} }}</td>
                                        <td class="text-sm d-none d-md-table-cell">{{ $item->{'719_kategori'} }}</td>
                                        <td class="text-sm text-end d-none d-md-table-cell">Rp {{ number_format($item->{'719_harga_beli'}, 0, ',', '.') }}</td>
                                        <td class="text-sm text-end">Rp {{ number_format($item->{'719_harga_jual'}, 0, ',', '.') }}</td>
                                        <td class="text-sm text-end">{{ $item->{'719_stok_tercatat'} }}</td>
                                        <td class="text-sm text-end d-none d-lg-table-cell">Rp {{ number_format($item->{'719_stok_tercatat'} * $item->{'719_harga_beli'}, 0, ',', '.') }}</td>
                                        <td class="text-sm text-end">
                                            @php
                                                $status = $item->{'719_stok_tercatat'} <= $item->{'719_stok_min'} ? 'warning' : 'success';
                                            @endphp
                                            <span class="badge badge-sm bg-gradient-{{ $status }}">
                                                {{ $status == 'warning' ? 'Menipis' : 'Aman' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-secondary text-xs">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end text-secondary text-xs">Total Nilai Stok</th>
                                    <th class="text-end d-none d-lg-table-cell">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== REKAP KEUANGAN ========== -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Rekap Keuangan <small class="text-secondary text-xs">({{ $start }} s/d {{ $end }})</small></h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <div class="alert alert-success text-white mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-arrow-down me-2"></i>
                                <div>
                                    <strong>Total Pemasukan</strong><br>
                                    Rp {{ number_format($totalMasuk, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="alert alert-danger text-white mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-arrow-up me-2"></i>
                                <div>
                                    <strong>Total Pengeluaran</strong><br>
                                    Rp {{ number_format($totalKeluar, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="alert alert-{{ $selisih >= 0 ? 'primary' : 'warning' }} text-white mb-0 d-flex align-items-center">
                                <i class="fa-solid fa-calculator me-2"></i>
                                <div>
                                    <strong>Selisih (Masuk - Keluar)</strong><br>
                                    Rp {{ number_format($selisih, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($dates->isNotEmpty())
                        <div class="table-responsive mt-3">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Tanggal</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Pemasukan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Pengeluaran</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dates as $row)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                                            <td class="text-end">Rp {{ number_format($row['masuk'], 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($row['keluar'], 0, ',', '.') }}</td>
                                            <td class="text-end fw-bold">Rp {{ number_format($row['saldo'], 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-secondary text-xs mt-2">Tidak ada transaksi pada rentang ini</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ========== AKTIVITAS PENGGUNA ========== -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Aktivitas Pengguna <small class="text-secondary text-xs">({{ $start }} s/d {{ $end }})</small></h6>
                    <span class="badge bg-secondary bg-gradient">{{ $activities->count() }} log</span>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Waktu</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengguna</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-md-table-cell">Model</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-lg-table-cell">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Perubahan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $log)
                                    <tr>
                                        <td class="text-xs">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-xs">{{ $log->user->name ?? 'System' }}</td>
                                        <td>
                                            @php
                                                $badge = match($log->action) {
                                                    'create' => 'success',
                                                    'update' => 'primary',
                                                    'delete' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge badge-sm bg-gradient-{{ $badge }}">{{ $log->action }}</span>
                                        </td>
                                        <td class="text-xs d-none d-md-table-cell">{{ class_basename($log->model_type) }}</td>
                                        <td class="text-xs d-none d-lg-table-cell">{{ $log->model_id ?? '-' }}</td>
                                        <td>
                                            @if($log->changes)
                                                <pre class="mb-0" style="font-size: 0.65rem; max-height:60px; overflow-y:auto;">{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                <span class="text-secondary text-xxs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-secondary text-xs">Tidak ada aktivitas</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
