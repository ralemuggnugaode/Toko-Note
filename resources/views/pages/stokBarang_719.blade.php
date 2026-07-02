@extends('pages.partials.main')
@section('page')
<div class="container-fluid py-2">

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
        <span class="alert-icon"><i class="fa-solid fa-check"></i></span>
        <span class="alert-text">{{ session('success') }}</span>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Daftar Barang</h6>
                    <button class="btn bg-gradient-primary btn-sm mb-0" data-bs-toggle="modal"
                        data-bs-target="#modalTambahBarang">
                        <i class="fa-solid fa-plus"></i> Tambah Barang
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode/Nama</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Gambar</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Harga Beli</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Harga Jual</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">Stok Tercatat</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangs as $barang)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $barang->{'719_nama'} }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $barang->{'719_kode'} }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm" value="{{ $barang->{'719_gambar'} }}"></td>
                                        <td class="text-sm">{{ $barang->{'719_kategori'} }}</td>
                                        <td class="text-sm text-end">Rp {{ number_format($barang->{'719_harga_beli'}, 0, ',', '.') }}</td>
                                        <td class="text-sm text-end">Rp {{ number_format($barang->{'719_harga_jual'}, 0, ',', '.') }}</td>
                                        <td class="text-sm text-end">
                                            <span class="badge badge-sm bg-gradient-{{ $barang->{'719_stok_tercatat'} <= $barang->{'719_stok_min'} ? 'warning' : 'success' }}">
                                                {{ $barang->{'719_stok_tercatat'} }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('stok-barang-719.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang {{ $barang->{'719_nama'} }}?')">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="btn btn-link text-danger px-2 mb-0">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada barang</td>
                                    </tr>
                                @endforelse
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
                <form action="{{ route('stok-barang-719.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Gambar Barang</label>
                            <input type="file" name="719_gambar" id="719_gambar" class="form-control" required>
                        </div>
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
</div>
@endsection
