@extends('pages.partials.main')
@section('page')
<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md me-3">
                        <i class="fa-regular fa-circle-user text-lg opacity-10" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Selamat datang <strong>{{ auth()->user()->name ?? 'Pengguna' }}</strong>!</h5>
                        <p class="text-secondary text-xs mb-0">Berikut ringkasan aktivitas hari ini</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Pemasukan Hari Ini</p>
                                <h5 class="font-weight-bolder mb-0">
                                    Rp {{ number_format($pemasukanHariIni, 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fa-solid fa-arrow-down text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengeluaran -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Pengeluaran Hari Ini</p>
                                <h5 class="font-weight-bolder mb-0">
                                    Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                <i class="fa-solid fa-arrow-up text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Barang -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Total Barang</p>
                                <h5 class="font-weight-bolder mb-0">{{ $totalBarang }}</h5>
                            </div>
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

        <!-- Stok Menipis -->
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Stok Menipis</p>
                                <h5 class="font-weight-bolder mb-0">{{ $stokMenipis }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fa-solid fa-triangle-exclamation text-lg opacity-10"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card z-index-2">
                <div class="card-header pb-0">
                    <h6>Omzet Penjualan 7 Hari Terakhir</h6>
                </div>
                <div class="card-body p-3">
                    @if($dates->isNotEmpty() && $omzetData->sum() > 0)
                        <div class="chart" style="position: relative; height: 300px;">
                            <canvas id="chart-line" class="chart-canvas"></canvas>
                        </div>
                    @else
                        <div class="text-center text-secondary text-xs py-4">
                            <i class="fa-solid fa-chart-line fa-2x mb-2 d-block"></i>
                            Belum ada data penjualan. Mulai catat!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('chart-line');
        if (!canvas) return; // Jika tidak ada canvas (karena data kosong), hentikan

        const labels = @json($dates);
        const data = @json($omzetData);

        // Jika data kosong, beri default agar grafik tetap tampil (tapi ini tidak akan terjadi karena sudah dicek di atas)
        let chartLabels = labels.length ? labels : ['Tidak ada data'];
        let chartData = data.length ? data : [0];

        new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: chartData,
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#4CAF50',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
