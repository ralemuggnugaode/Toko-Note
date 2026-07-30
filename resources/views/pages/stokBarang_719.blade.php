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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                <span class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                <span class="alert-text">
                    <strong>Gagal menyimpan data!</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </span>
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
                        <div class="table-responsive-md p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kode/Nama</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 d-none d-md-table-cell">
                                            Gambar</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Kategori</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end d-none d-md-table-cell">
                                            Harga Beli</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                            Harga Jual</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                            Stok</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-end">
                                            Aksi</th>
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
                                            <td class="text-sm d-none d-md-table-cell">
                                                <img width="40" class="img-thumbnail"
                                                    src="{{ asset('storage/' . $barang->{'719_gambar'}) }}" alt="">
                                            </td>
                                            <td class="text-sm">{{ $barang->{'719_kategori'} }}</td>
                                            <td class="text-sm text-end d-none d-md-table-cell">
                                                Rp {{ number_format($barang->{'719_harga_beli'}, 0, ',', '.') }}
                                            </td>
                                            <td class="text-sm text-end">
                                                Rp {{ number_format($barang->{'719_harga_jual'}, 0, ',', '.') }}
                                            </td>
                                            <td class="text-sm text-end">
                                                <span
                                                    class="badge badge-sm bg-gradient-{{ $barang->{'719_stok_tercatat'} <= $barang->{'719_stok_min'} ? 'warning' : 'success' }}">
                                                    {{ $barang->{'719_stok_tercatat'} }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <button class="btn btn-link text-secondary px-2 mb-0" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('stok-barang-719.edit', $barang->id) }}">
                                                                <i class="fa-regular fa-pen-to-square me-2"></i> Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('stok-barang-719.destroy', $barang->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus barang {{ $barang->{'719_nama'} }}?')">
                                                                @method('delete')
                                                                @csrf
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="fa-regular fa-trash-can me-2"></i> Hapus
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada barang</td>
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
                    <form action="{{ route('stok-barang-719.store') }}" method="POST" enctype="multipart/form-data"
                        novalidate>
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Gambar Barang <span class="text-danger">*</span></label>
                                <input type="file" name="719_gambar" id="719_gambar"
                                    class="form-control @error('719_gambar') is-invalid @enderror" accept="image/*"
                                    required>
                                <small class="form-text text-muted">Maksimal 5 MB, format: jpg, png, gif</small>
                                @error('719_gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="719_nama"
                                    class="form-control @error('719_nama') is-invalid @enderror"
                                    value="{{ old('719_nama') }}" placeholder="Masukkan nama barang" maxlength="255"
                                    required>
                                @error('719_nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <input type="text" name="719_kategori"
                                    class="form-control @error('719_kategori') is-invalid @enderror"
                                    value="{{ old('719_kategori') }}" placeholder="Contoh: Elektronik" maxlength="255"
                                    required>
                                @error('719_kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                    <input type="number" name="719_harga_beli"
                                        class="form-control @error('719_harga_beli') is-invalid @enderror"
                                        value="{{ old('719_harga_beli') }}" placeholder="0" min="0" step="1" required>
                                    @error('719_harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                    <input type="number" name="719_harga_jual"
                                        class="form-control @error('719_harga_jual') is-invalid @enderror"
                                        value="{{ old('719_harga_jual') }}" placeholder="0" min="0" step="1" required>
                                    @error('719_harga_jual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stok Barang <span class="text-danger">*</span></label>
                                <input type="number" name="719_stok_tercatat"
                                    class="form-control @error('719_stok_tercatat') is-invalid @enderror"
                                    value="{{ old('719_stok_tercatat', 0) }}" placeholder="0" min="0" step="1" required>
                                @error('719_stok_tercatat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                                <input type="number" name="719_stok_min"
                                    class="form-control @error('719_stok_min') is-invalid @enderror"
                                    value="{{ old('719_stok_min', 5) }}" placeholder="5" min="0" step="1" required>
                                @error('719_stok_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var tambahModal = new bootstrap.Modal(document.getElementById('modalTambahBarang'));
                tambahModal.show();
            });
        </script>
    @endif
@endsection
