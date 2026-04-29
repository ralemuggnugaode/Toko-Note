@extends('partials.main')
@section('page')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card mb-4 p-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Stok Barang</h5>
                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-1"></i> Tambah Barang
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-2">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Nama Barang</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Stok</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Looping data dummy, ganti dengan data dari controller -->
                                <tr>
                                    <td class="text-xs font-weight-bold">1</td>
                                    <td class="text-xs font-weight-bold">Beras Pandan Wangi</td>
                                    <td class="text-xs">Sembako</td>
                                    <td class="text-xs">50</td>
                                    <td class="text-xs">Rp 15.000</td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button
                                                class="btn btn-link text-secondary mb-0 dropdown-toggle"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                        onclick="return confirm('Edit barang ini?')">
                                                        <i class="fas fa-pen me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#"
                                                        onclick="return confirm('Hapus barang ini?')">
                                                        <i class="fas fa-trash me-2"></i> Hapus
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-xs font-weight-bold">2</td>
                                    <td class="text-xs font-weight-bold">Minyak Goreng 1L</td>
                                    <td class="text-xs">Sembako</td>
                                    <td class="text-xs">120</td>
                                    <td class="text-xs">Rp 18.000</td>
                                    <td class="align-middle">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary mb-0 dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"
                                                        onclick="return confirm('Edit barang ini?')"><i
                                                            class="fas fa-pen me-2"></i> Edit</a></li>
                                                <li><a class="dropdown-item text-danger" href="#"
                                                        onclick="return confirm('Hapus barang ini?')"><i
                                                            class="fas fa-trash me-2"></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Tambah data lain sesuai kebutuhan -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Barang -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Tambah Barang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" method="POST"> <!-- Sesuaikan route -->
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_barang" class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" id="nama_barang" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-control" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Sembako">Sembako</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Alat Tulis">Alat Tulis</option>
                                <!-- Loop kategori dari DB -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" min="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-gradient-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
