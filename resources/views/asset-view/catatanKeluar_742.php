<?php
require_once __DIR__ . '/../classes/stok_719.php';
require_once __DIR__ . '/../classes/keluar_742.php';

$page = 'keluar';

$objekStok = new stok_719([]);
$objekStok->isiDummy();
$daftarBarang = $objekStok->getAll();

$objekKeluar = new keluar_742([]);
$objekKeluar->seedDummyToFile();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tanggal'])) {
    $items = [];
    $barangIds = $_POST['barang_id'];
    $jumlahs = $_POST['jumlah'];
    $hargas = $_POST['harga'];
    
    for ($i = 0; $i < count($barangIds); $i++) {
        if (!empty($barangIds[$i]) && !empty($jumlahs[$i])) {
            $items[] = [
                '742_barang_id' => $barangIds[$i],
                '742_jumlah' => (int)$jumlahs[$i],
                '742_harga' => (int)$hargas[$i],
                '742_subtotal' => (int)$jumlahs[$i] * (int)$hargas[$i]
            ];
        }
    }
    
    $dataKeluar = [
        '742_tanggal' => $_POST['tanggal'],
        '742_pihak' => $_POST['pihak'],
        '742_nomor' => $_POST['nomor'] ?? 'OUT-' . date('Ymd') . '-' . rand(100, 999),
        '742_items' => $items,
        '742_total' => (int)$_POST['total'],
        '742_keterangan' => $_POST['keterangan'] ?? ''
    ];
    
    $catatanKeluar = new keluar_742($dataKeluar);
    $catatanKeluar->simpan();
    
    header('Location: catatanKeluar_742.php?success=1');
    exit;
}

$keluarTerakhir = $objekKeluar->getLastFive();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'partials/head.php'; ?>
</head>
<body class="g-sidenav-show bg-gray-100">
    <?php include 'partials/sidebar.php'; ?>
    <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
        <?php include 'partials/navbar.php'; ?>
        <div class="container-fluid py-2">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                    <span class="alert-text"><strong>Berhasil!</strong> Catatan keluar berhasil disimpan!</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Catatan Keluar</h6>
                        </div>
                        <div class="card-body px-4 pt-4 pb-2">
                            <form action="" method="POST" id="formKeluar">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Tanggal</label>
                                        <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Pihak</label>
                                        <input type="text" name="pihak" class="form-control" placeholder="Customer/Pembeli" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nomor (opsional)</label>
                                        <input type="text" name="nomor" class="form-control" placeholder="NOTA-K001">
                                    </div>
                                </div>
                                <hr class="horizontal dark">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
                                <div id="items-container-keluar">
                                    <div class="row item-row-keluar mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Jenis</label>
                                            <select name="barang_id[]" class="form-control pilih-barang-keluar" required>
                                                <option value="">Pilih jenis</option>
                                                <?php foreach ($daftarBarang as $barang): ?>
                                                    <option value="<?= htmlspecialchars($barang['719_kode']) ?>"
                                                        data-harga="<?= $barang['719_harga_jual'] ?>"
                                                        data-stok="<?= $barang['719_stok_tercatat'] ?>">
                                                        <?= htmlspecialchars($barang['719_nama']) ?> - Stok: <?= $barang['719_stok_tercatat'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-secondary stok-info"></small>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" name="jumlah[]" class="form-control jumlah" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Harga</label>
                                            <input type="number" name="harga[]" class="form-control harga-satuan" required>
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button type="button" class="btn btn-link text-danger remove-item p-0">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="add-item-keluar" class="btn btn-outline-primary btn-sm mt-2">
                                    <i class="fa-solid fa-plus"></i> Tambah Barang
                                </button>
                                <hr class="horizontal dark">
                                <div class="row align-items-center">
                                    <div class="col-md-9">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" rows="2" class="form-control"></textarea>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <h6 class="mb-0">Total: <span id="total-display-keluar">Rp 0</span></h6>
                                        <input type="hidden" name="total" id="total-hidden-keluar" value="0">
                                        <button type="submit" class="btn bg-gradient-danger btn-lg mt-2">Simpan Catatan Keluar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Riwayat Catatan Keluar</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="list-group list-group-flush">
                                <?php if (empty($keluarTerakhir)): ?>
                                    <p class="text-sm text-center text-secondary">Belum ada catatan keluar</p>
                                <?php else: ?>
                                    <?php foreach ($keluarTerakhir as $keluar): ?>
                                        <div class="list-group-item border-0 px-0">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-sm font-weight-bold"><?= htmlspecialchars($keluar['742_nomor']) ?></span>
                                                <small class="text-secondary"><?= date('d/m/Y', strtotime($keluar['742_tanggal'])) ?></small>
                                            </div>
                                            <p class="text-xs mb-1"><?= htmlspecialchars($keluar['742_pihak']) ?></p>
                                            <div class="d-flex justify-content-between">
                                                <p class="text-xs mb-1 text-danger font-weight-bold">Rp <?= number_format($keluar['742_total'], 0, ',', '.') ?></p>
                                                <?php if (!empty($keluar['742_keterangan'])): ?>
                                                    <p class="text-xs mb-0 text-secondary">
                                                        <i class="fa fa-pencil-alt me-1"></i>
                                                        <?= htmlspecialchars($keluar['742_keterangan']) ?>
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
    <script>
        function hitungTotalKeluar() {
            let total = 0;
            document.querySelectorAll('.item-row-keluar').forEach(row => {
                const jumlah = parseInt(row.querySelector('.jumlah').value) || 0;
                const harga = parseInt(row.querySelector('.harga-satuan').value) || 0;
                total += jumlah * harga;
            });
            document.getElementById('total-display-keluar').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('total-hidden-keluar').value = total;
        }

        document.addEventListener('input', function (e) {
            if (e.target.closest('.item-row-keluar') && (e.target.classList.contains('jumlah') || e.target.classList.contains('harga-satuan'))) {
                hitungTotalKeluar();
            }
        });

        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('pilih-barang-keluar')) {
                const opt = e.target.selectedOptions[0];
                const harga = opt.getAttribute('data-harga');
                const stok = opt.getAttribute('data-stok');
                const row = e.target.closest('.item-row-keluar');
                row.querySelector('.harga-satuan').value = harga || '';
                row.querySelector('.stok-info').innerText = stok ? 'Stok tercatat: ' + stok : '';
                hitungTotalKeluar();
            }
        });

        document.getElementById('add-item-keluar').addEventListener('click', function () {
            const container = document.getElementById('items-container-keluar');
            const firstRow = container.querySelector('.item-row-keluar');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            newRow.querySelector('.jumlah').value = 1;
            newRow.querySelector('.harga-satuan').value = '';
            newRow.querySelector('.stok-info').innerText = '';
            container.appendChild(newRow);
            hitungTotalKeluar();
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-item')) {
                const row = e.target.closest('.item-row-keluar');
                if (document.querySelectorAll('.item-row-keluar').length > 1) {
                    row.remove();
                    hitungTotalKeluar();
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

        hitungTotalKeluar();
    </script>
</body>
</html>