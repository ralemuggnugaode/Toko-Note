@extends('partials.main')
@section('page')
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
              <tr>
                <td>
                  <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm">nama barang</h6>
                      <p class="text-xs text-secondary mb-0">kode barang</p>
                    </div>
                  </div>
                </td>
                <td class="text-sm">kategori</td>
                <td class="text-sm text-end">Rp harga beli</td>
                <td class="text-sm text-end">Rp harga jual</td>
                <td class="text-sm text-end">
                  <span class="badge badge-sm bg-gradient-success">
                    stok tercatat
                  </span>
                </td>
                <td class="text-end">
                  <a href="#" class="btn btn-link text-dark px-3 mb-0">
                    <i class="fa-solid fa-list text-dark me-2"></i>Kartu
                  </a>
                </td>
              </tr>

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
      <form action="#" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kategori</label>
            <input type="text" name="kategori" class="form-control">
          </div>
          <div class="row">
            <div class="col-6">
              <label class="form-label">Harga Beli</label>
              <input type="number" name="harga_beli" class="form-control">
            </div>
            <div class="col-6">
              <label class="form-label">Harga Jual</label>
              <input type="number" name="harga_jual" class="form-control">
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label">Stok Minimum</label>
            <input type="number" name="stok_min" value="5" class="form-control">
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
@endsection
