<?php
require_once __DIR__ . '/../classes/stok_719.php';
require_once __DIR__ . '/../classes/masuk_729.php';

$page = 'masuk';

$objekStok = new stok_719([]);
$objekStok->isiDummy();
$daftarBarang = $objekStok->getAll();

$obj729Masuk = new masuk_729([]);
$obj729Masuk->initDummy();          // Buat 5 dummy di JSON jika kosong

// Panggil method untuk demonstrasi array_push (poin 4) – tidak ditampilkan
$dataDummy = $obj729Masuk->tampilkanDummy();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['729_tanggal'])) {
    $items = [];
    $barangIds = $_POST['729_barang_id'];
    $jumlahs = $_POST['729_jumlah'];
    $hargas = $_POST['729_harga'];
    $namaBarangLain = $_POST['729_nama_barang_lain'] ?? [];

    for ($i = 0; $i < count($barangIds); $i++) {
        if (!empty($barangIds[$i]) && !empty($jumlahs[$i])) {
            $item = [
                '729_barang_id' => $barangIds[$i],
                '729_jumlah' => (int)$jumlahs[$i],
                '729_harga' => (int)$hargas[$i],
                '729_subtotal' => (int)$jumlahs[$i] * (int)$hargas[$i]
            ];
            if ($barangIds[$i] === 'LAINNYA') {
                $item['729_nama_barang_lain'] = trim($namaBarangLain[$i] ?? '');
            }
            $items[] = $item;
        }
    }
    
    $dataMasuk = [
        '729_tanggal' => $_POST['729_tanggal'],
        '729_pihak' => $_POST['729_pihak'],
        '729_nomor' => $_POST['729_nomor'] ?? 'IN-' . date('Ymd') . '-' . rand(100, 999),
        '729_items' => $items,
        '729_total' => (int)$_POST['729_total'],
        '729_keterangan' => $_POST['729_keterangan'] ?? ''
    ];
    
    $catatanMasuk = new masuk_729($dataMasuk);
    $catatanMasuk->simpan();
    
    header('Location: catatan_masuk.php?success=1');
    exit;
}

$masukTerakhir = $obj729Masuk->getLastFive();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include 'partials/head.php'; ?>
</head>

<body class="g-sidenav-show  bg-gray-100">
    <?php include 'partials/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
        <?php include 'partials/navbar.php'; ?>
        <div class="container-fluid py-2">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                    <span class="alert-text"><strong>Berhasil!</strong> Catatan masuk berhasil disimpan!</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Kolom Kiri: Form -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Catatan Masuk</h6>
                        </div>
                        <div class="card-body px-4 pt-4 pb-2">
                            <form action="" method="POST" id="formMasuk">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" name="729_tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pihak</label>
                                        <input type="text" name="729_pihak" class="form-control" placeholder="Supplier" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nomor (opsional)</label>
                                        <input type="text" name="729_nomor" class="form-control" placeholder="NOTA-M001">
                                    </div>
                                </div>
                                <hr class="horizontal dark">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
                                <div id="items-container-masuk">
                                    <div class="row item-row-masuk mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Jenis</label>
                                            <select name="729_barang_id[]" class="form-control pilih-barang-masuk" required>
                                                <option value="">Pilih jenis</option>
                                                <?php foreach ($daftarBarang as $barang): ?>
                                                    <option value="<?= htmlspecialchars($barang['719_kode']) ?>"
                                                        data-harga="<?= $barang['719_harga_beli'] ?? 0 ?>"
                                                        data-stok="<?= $barang['719_stok_tercatat'] ?>">
                                                        <?= htmlspecialchars($barang['719_nama']) ?> - Stok: <?= $barang['719_stok_tercatat'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                                <option value="LAINNYA" data-harga="0" data-stok="">Lain-lain</option>
                                            </select>
                                            <small class="text-secondary stok-info-masuk"></small>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" name="729_jumlah[]" class="form-control jumlah-masuk" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Harga</label>
                                            <input type="number" name="729_harga[]" class="form-control harga-masuk" required>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="nama-lain-container" style="display:none; width:100%; margin-right:5px;">
                                                <input type="text" name="729_nama_barang_lain[]" class="form-control nama-barang-lain" placeholder="Nama barang baru" disabled>
                                            </div>
                                            <button type="button" class="btn btn-link text-danger remove-item-masuk p-0">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-item-masuk" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="fa-solid fa-plus"></i> Tambah Barang
                                </button>
                                <hr class="horizontal dark">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="729_keterangan" rows="2" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <h6 class="mb-0">Total: <span id="total-display-masuk">Rp 0</span></h6>
                                        <input type="hidden" name="729_total" id="total-hidden-masuk" value="0">
                                        <button type="submit" class="btn bg-gradient-success btn-lg mt-2">Simpan Catatan Masuk</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Daftar 5 Catatan Masuk Terakhir -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>5 Catatan Masuk Terakhir</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="list-group list-group-flush">
                                <?php if (empty($masukTerakhir)): ?>
                                    <p class="text-sm text-center text-secondary">Belum ada catatan masuk</p>
                                <?php else: ?>
                                    <?php foreach ($masukTerakhir as $masuk): ?>
                                        <div class="list-group-item border-0 px-0">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-sm font-weight-bold"><?= htmlspecialchars($masuk['729_nomor']) ?></span>
                                                <small class="text-secondary"><?= date('d/m/Y', strtotime($masuk['729_tanggal'])) ?></small>
                                            </div>
                                            <p class="text-xs mb-1"><?= htmlspecialchars($masuk['729_pihak']) ?></p>
                                            <div class="d-flex justify-content-between">
                                                <p class="text-xs text-success font-weight-bold mb-0">Rp <?= number_format($masuk['729_total'], 0, ',', '.') ?></p>
                                                <?php if (!empty($masuk['729_keterangan'])): ?>
                                                    <p class="text-xs mb-0 text-secondary">
                                                        <i class="fa fa-pencil-alt me-1"></i> 
                                                        <?= htmlspecialchars($masuk['729_keterangan']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/chartjs.min.js"></script>
    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");
        new Chart(ctx, { /* ... */ });
        var ctx2 = document.getElementById("chart-line").getContext("2d");
        new Chart(ctx2, { /* ... */ });
    </script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
        }
    </script>
    <script src="/assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>

    <script>
        function hitungTotalMasuk() {
            let total = 0;
            document.querySelectorAll('.item-row-masuk').forEach(row => {
                const jumlah = parseInt(row.querySelector('.jumlah-masuk').value) || 0;
                const harga = parseInt(row.querySelector('.harga-masuk').value) || 0;
                total += jumlah * harga;
            });
            document.getElementById('total-display-masuk').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total-hidden-masuk').value = total;
        }

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('pilih-barang-masuk')) {
                const row = e.target.closest('.item-row-masuk');
                const namaContainer = row.querySelector('.nama-lain-container');
                const namaInput = row.querySelector('.nama-barang-lain');
                const hargaInput = row.querySelector('.harga-masuk');
                const stokInfo = row.querySelector('.stok-info-masuk');

                if (e.target.value === 'LAINNYA') {
                    namaContainer.style.display = 'block';
                    namaInput.disabled = false;
                    namaInput.required = true;
                    hargaInput.value = '';
                    hargaInput.required = true;
                    stokInfo.innerText = '';
                } else {
                    namaContainer.style.display = 'none';
                    namaInput.disabled = true;
                    namaInput.required = false;
                    namaInput.value = '';
                    const opt = e.target.selectedOptions[0];
                    const harga = opt.getAttribute('data-harga');
                    const stok = opt.getAttribute('data-stok');
                    hargaInput.value = harga || '';
                    hargaInput.required = true;
                    stokInfo.innerText = stok ? 'Stok tercatat: ' + stok : '';
                }
                hitungTotalMasuk();
            }
        });

        document.addEventListener('input', function (e) {
            if (e.target.closest('.item-row-masuk') && (e.target.classList.contains('jumlah-masuk') || e.target.classList.contains('harga-masuk'))) {
                hitungTotalMasuk();
            }
        });

        document.getElementById('add-item-masuk').addEventListener('click', function () {
            const container = document.getElementById('items-container-masuk');
            const firstRow = container.querySelector('.item-row-masuk');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input, select').forEach(input => {
                if (input.type !== 'button') input.value = '';
            });
            newRow.querySelector('.jumlah-masuk').value = 1;
            newRow.querySelector('.harga-masuk').value = '';
            newRow.querySelector('.pilih-barang-masuk').value = '';
            const namaContainer = newRow.querySelector('.nama-lain-container');
            namaContainer.style.display = 'none';
            const namaInput = newRow.querySelector('.nama-barang-lain');
            namaInput.disabled = true;
            namaInput.required = false;
            namaInput.value = '';
            const stokInfo = newRow.querySelector('.stok-info-masuk');
            if (stokInfo) stokInfo.innerText = '';
            container.appendChild(newRow);
            hitungTotalMasuk();
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-item-masuk')) {
                const row = e.target.closest('.item-row-masuk');
                if (document.querySelectorAll('.item-row-masuk').length > 1) {
                    row.remove();
                    hitungTotalMasuk();
                }
            }
        });

        window.addEventListener('load', function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 150);
                }, 2000);
            }
        });

        hitungTotalMasuk();
    </script>
</body>

</html>