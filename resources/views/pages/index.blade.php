@extends('pages.partials.main')
@section('page')
<div class="row">
    <!-- Kartu Pemasukan -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Pemasukan Hari Ini</p>
                            <h5 class="font-weight-bolder mb-0">
                                Rp {{ number_format($pemasukanHariIni, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                            <i class="fa-solid fa-arrow-down text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Pengeluaran -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Pengeluaran Hari Ini</p>
                            <h5 class="font-weight-bolder mb-0">
                                Rp {{ number_format($pengeluaranHariIni, 0, ',', '.') }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-danger shadow text-center border-radius-md">
                            <i class="fa-solid fa-arrow-up text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Total Barang -->
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Barang</p>
                            <h5 class="font-weight-bolder mb-0">{{ $totalBarang }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                            <i class="fa-solid fa-box text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kartu Stok Menipis -->
    <div class="col-xl-3 col-sm-6">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Stok Menipis</p>
                            <h5 class="font-weight-bolder mb-0">{{ $stokMenipis }}</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                            <i class="fa-solid fa-triangle-exclamation text-lg opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Omzet -->
<div class="row mt-4">
    <div class="col-lg-7 mb-lg-0 mb-4">
        <div class="card z-index-2">
            <div class="card-header pb-0">
                <h6>Omzet Penjualan 7 Hari Terakhir</h6>
            </div>
            <div class="card-body p-3">
                <div class="chart">
                    <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Pastikan Chart.js sudah diload (misal dari CDN) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chart-line').getContext('2d');

        const labels = @json($dates);
        const data = @json($omzetData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: data,
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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
