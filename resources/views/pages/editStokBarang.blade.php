@extends('pages.partials.main')
@section('page')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Edit Barang</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stok-barang-719.update', $stokBarang) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Gambar Barang</label>
                            <br>
                            @if ($stokBarang->{'719_gambar'})
                                <img src="{{ asset('storage/' . $stokBarang->{'719_gambar'}) }}" width="150" class="mb-2" alt="Gambar barang">
                                <br>
                            @endif
                            <input type="file" name="719_gambar" id="719_gambar" class="form-control">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar. Maks 5 MB.</small>
                            @error('719_gambar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kode --}}
                        <div class="mb-3">
                            <label class="form-label">Kode Barang</label>
                            <input type="text" name="719_kode" class="form-control @error('719_kode') is-invalid @enderror"
                                value="{{ old('719_kode', $stokBarang->{'719_kode'}) }}" placeholder="Opsional, otomatis jika kosong" disabled>
                            @error('719_kode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" name="719_nama" class="form-control @error('719_nama') is-invalid @enderror"
                                value="{{ old('719_nama', $stokBarang->{'719_nama'}) }}" required>
                            @error('719_nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="719_kategori" class="form-control @error('719_kategori') is-invalid @enderror"
                                value="{{ old('719_kategori', $stokBarang->{'719_kategori'}) }}" required>
                            @error('719_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Harga --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                    <input type="number" name="719_harga_beli" class="form-control @error('719_harga_beli') is-invalid @enderror"
                                        value="{{ old('719_harga_beli', $stokBarang->{'719_harga_beli'}) }}" min="0" required>
                                    @error('719_harga_beli')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                    <input type="number" name="719_harga_jual" class="form-control @error('719_harga_jual') is-invalid @enderror"
                                        value="{{ old('719_harga_jual', $stokBarang->{'719_harga_jual'}) }}" min="0" required>
                                    @error('719_harga_jual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Stok --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Stok Tercatat <span class="text-danger">*</span></label>
                                    <input type="number" name="719_stok_tercatat" class="form-control @error('719_stok_tercatat') is-invalid @enderror"
                                        value="{{ old('719_stok_tercatat', $stokBarang->{'719_stok_tercatat'}) }}" min="0" required>
                                    @error('719_stok_tercatat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                                    <input type="number" name="719_stok_min" class="form-control @error('719_stok_min') is-invalid @enderror"
                                        value="{{ old('719_stok_min', $stokBarang->{'719_stok_min'}) }}" min="0" required>
                                    @error('719_stok_min')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('stok-barang-719.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Update Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
