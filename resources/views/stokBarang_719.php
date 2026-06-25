<?php
require_once __DIR__ . '/../classes/stok_719.php';

$page = 'stok';
$objek_719 = new stok_719([]);
$objek_719->isiDummy();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['719_nama'])) {
  $dataBaru = [
    '719_nama' => $_POST['719_nama'],
    '719_kategori' => $_POST['719_kategori'],
    '719_harga_beli' => $_POST['719_harga_beli'],
    '719_harga_jual' => $_POST['719_harga_jual'],
    '719_stok_min' => $_POST['719_stok_min'],
    '719_stok_tercatat' => $_POST['719_stok_tercatat'],
    '719_kode' => $kodeAsli ?? 'BRG719' . rand(100, 999)
  ];
  $barangBaru = new stok_719($dataBaru);
  $barangBaru->simpan();
  header('Location: stokBarang_719.php?success=add');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_hapus'])) {
  $kodeHapus = $_POST['kode_hapus'];
  $objek_719->hapus($kodeHapus);
  header('Location: stokBarang_719.php?success=hapus');
  exit;
}

$alertMessage = '';
if (isset($_GET['success'])) {
  if ($_GET['success'] === 'add') {
    $alertMessage = 'Barang berhasil ditambahkan!';
  } elseif ($_GET['success'] === 'hapus') {
    $alertMessage = 'Barang berhasil dihapus!';
  }
}

$daftarbarang = $objek_719->getAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../pages/partials/head.php'; ?>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <?php include '../pages/partials/sidebar.php'; ?>
  <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <?php include '../pages/partials/navbar.php' ?>
    <div class="container-fluid py-2">
      <?php if($alertMessage): ?>
        <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
          <span class="alert-icon"><i class="fa-solid fa-check"></i></span>
          <span class="alert-text"><?= htmlspecialchars($alertMessage) ?></span>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h6>Daftar Barang</h6>
              <button class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#modalTambahBarang">
                <i class="fa-solid fa-plus"></i> Tambah Barang
              </button>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode/Nama</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Harga Beli</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Harga Jual</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Stok Tercatat</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($daftarbarang)) : ?>
                      <tr>
                        <td colspan="5" class="text-center">Belum ada barang</td>
                      </tr>
                      <?php else: foreach ($daftarbarang as $d) : ?>
                        <tr>
                          <td>
                            <div class="d-flex px-2 py-1">
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm"><?= htmlspecialchars($d['719_nama']) ?></h6>
                                <p class="text-xs text-secondary mb-0"><?= $d['719_kode'] ?></p>
                              </div>
                            </div>
                          </td>
                          <td class="text-sm"><?= htmlspecialchars($d['719_kategori']) ?></td>
                          <td class="text-sm text-end">Rp <?= number_format($d['719_harga_beli'], 0, ',', '.') ?></td>
                          <td class="text-sm text-end">Rp <?= number_format($d['719_harga_jual'], 0, ',', '.') ?></td>
                          <td class="text-sm text-end">
                            <span class="badge badge-sm bg-gradient-<?= $d['719_stok_tercatat'] <= $d['719_stok_min'] ? 'warning' : 'success' ?>">
                              <?= $d['719_stok_tercatat'] ?>
                            </span>
                          </td>
                          <td class="text-end">
                            <button type="button" class="btn btn-link text-danger px-2 mb-0 btn-hapus"
                              data-kode="<?= $d['719_kode'] ?>"
                              data-nama="<?= htmlspecialchars($d['719_nama']) ?>"
                              title="Hapus">
                              <i class="fa-solid fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                    <?php endforeach;
                    endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalTambahBarang" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Barang Baru</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Nama Barang</label>
                  <input type="text" name="719_nama" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Kategori</label>
                  <input type="text" name="719_kategori" class="form-control" required>
                </div>
                <div class="row">
                  <div class="col-6">
                    <label class="form-label">Harga Beli</label>
                    <input type="number" name="719_harga_beli" class="form-control" required>
                  </div>
                  <div class="col-6">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" name="719_harga_jual" class="form-control" required>
                  </div>
                </div>
                <div class="mt-3">
                  <label class="form-label">Stok Barang</label>
                  <input type="number" name="719_stok_tercatat" placeholder="0" class="form-control">
                </div>
                <div class="mt-3">
                  <label class="form-label">Stok Minimum</label>
                  <input type="number" name="719_stok_min" placeholder="5" class="form-control">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn bg-gradient-primary">Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" id="modalHapus" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Konfirmasi Hapus</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
              <div class="modal-body">
                <p>Yakin ingin menghapus barang <strong id="namaHapus"></strong> (kode: <span id="kodeHapusText"></span>)?</p>
                <input type="hidden" name="kode_hapus" id="kode_hapus">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn bg-gradient-danger">Hapus</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    </div>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Sales",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#fff",
          data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
          maxBarThickness: 6
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 15,
              font: {
                size: 14,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
              color: "#fff"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              display: false
            },
          },
        },
      },
    });


    var ctx2 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

    var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

    gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
    gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
            label: "Mobile apps",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#cb0c9f",
            borderWidth: 3,
            backgroundColor: gradientStroke1,
            fill: true,
            data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
            maxBarThickness: 6

          },
          {
            label: "Websites",
            tension: 0.4,
            borderWidth: 0,
            pointRadius: 0,
            borderColor: "#3A416F",
            borderWidth: 3,
            backgroundColor: gradientStroke2,
            fill: true,
            data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
            maxBarThickness: 6
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#b2b9bf',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#b2b9bf',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }

    document.querySelectorAll('.btn-hapus').forEach(btn => {
      btn.addEventListener('click', function() {
        const kode = this.dataset.kode;
        const nama = this.dataset.nama;
        document.getElementById('kode_hapus').value = kode;
        document.getElementById('namaHapus').textContent = nama;
        document.getElementById('kodeHapusText').textContent = kode;

        const modal = new bootstrap.Modal(document.getElementById('modalHapus'));
        modal.show();
      });
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
  </script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>