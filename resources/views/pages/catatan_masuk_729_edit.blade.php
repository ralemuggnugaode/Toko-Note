@extends('pages.partials.main')

@section('page')

<div class="container-fluid py-2">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Edit Catatan Masuk</h6>
                    <a href="{{ route('catatan-masuk.index') }}" class="btn btn-sm btn-outline-secondary mb-0">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="card-body px-4 pt-4 pb-2">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="alert alert-info text-xs">
                        <i class="fa fa-info-circle"></i> Mengubah jumlah/jenis barang akan otomatis menyesuaikan stok: stok lama dibatalkan, lalu stok baru diterapkan.
                    </div>
                    <form action="{{ route('catatan-masuk.update', $masuk->id) }}" method="POST" id="formEditMasuk" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="729_tanggal" class="form-control" value="{{ old('729_tanggal', $masuk->tanggal->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pihak</label>
                                <input type="text" name="729_pihak" class="form-control" placeholder="Supplier" value="{{ old('729_pihak', $masuk->pihak) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nomor (opsional)</label>
                                <input type="text" name="729_nomor" class="form-control" placeholder="NOTA-M001" value="{{ old('729_nomor', $masuk->nomor) }}">
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
                        <div id="items-container-masuk">
                            @forelse($masuk->items ?? [] as $item)
                                <div class="item-row-masuk mb-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label">Jenis</label>
                                            <select name="729_barang_id[]" class="form-control pilih-barang-masuk" required>
                                                <option value="">Pilih jenis</option>
                                                @foreach($daftarBarang as $barang)
                                                    <option value="{{ $barang->{'719_kode'} }}" data-harga="{{ $barang->{'719_harga_beli'} ?? 0 }}" data-stok="{{ $barang->{'719_stok_tercatat'} }}" data-nama="{{ $barang->{'719_nama'} }}" {{ ($item['barang_id'] ?? '') == $barang->{'719_kode'} ? 'selected' : '' }}>
                                                        {{ $barang->{'719_nama'} }} - Stok: {{ $barang->{'719_stok_tercatat'} }}
                                                    </option>
                                                @endforeach
                                                <option value="LAINNYA" data-harga="0" data-stok="" data-nama="" {{ ($item['barang_id'] ?? '') == 'LAINNYA' ? 'selected' : '' }}>Lain-lain</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" name="729_jumlah[]" class="form-control jumlah-masuk" min="1" value="{{ $item['jumlah'] ?? 1 }}" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Harga</label>
                                            <input type="number" name="729_harga[]" class="form-control harga-masuk" value="{{ $item['harga'] ?? '' }}" required>
                                            <input type="hidden" name="729_nama_barang[]" class="nama-barang-hidden-masuk" value="{{ ($item['barang_id'] ?? '') != 'LAINNYA' ? ($item['nama_barang'] ?? '') : '' }}">
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <div class="nama-lain-container" style="{{ ($item['barang_id'] ?? '') == 'LAINNYA' ? '' : 'display:none;' }} width:100%; margin-right:5px;">
                                                <input type="text" name="729_nama_barang_lain[]" class="form-control nama-barang-lain" placeholder="Nama barang baru" value="{{ $item['nama_barang_lain'] ?? '' }}" {{ ($item['barang_id'] ?? '') == 'LAINNYA' ? '' : 'disabled' }}>
                                            </div>
                                            <button type="button" class="btn btn-link text-danger remove-item-masuk p-0">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-secondary stok-info-masuk"></small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="item-row-masuk mb-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label">Jenis</label>
                                            <select name="729_barang_id[]" class="form-control pilih-barang-masuk" required>
                                                <option value="">Pilih jenis</option>
                                                @foreach($daftarBarang as $barang)
                                                    <option value="{{ $barang->{'719_kode'} }}" data-harga="{{ $barang->{'719_harga_beli'} ?? 0 }}" data-stok="{{ $barang->{'719_stok_tercatat'} }}" data-nama="{{ $barang->{'719_nama'} }}">
                                                        {{ $barang->{'719_nama'} }} - Stok: {{ $barang->{'719_stok_tercatat'} }}
                                                    </option>
                                                @endforeach
                                                <option value="LAINNYA" data-harga="0" data-stok="" data-nama="">Lain-lain</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" name="729_jumlah[]" class="form-control jumlah-masuk" min="1" value="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Harga</label>
                                            <input type="number" name="729_harga[]" class="form-control harga-masuk" required>
                                            <input type="hidden" name="729_nama_barang[]" class="nama-barang-hidden-masuk" value="">
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
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-secondary stok-info-masuk"></small>
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="add-item-masuk" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fa-solid fa-plus"></i> Tambah Barang
                        </button>
                        <hr class="horizontal dark">

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Gambar (opsional, kosongkan jika tidak ganti)</label>
                                <input type="file" name="729_gambar" class="form-control-file" accept="image/*">
                                <small class="text-secondary">Max 5MB, format jpg/png/jpeg/gif</small>
                                @if($masuk->gambar)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $masuk->gambar) }}" alt="Gambar saat ini" style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
                                        <span class="text-xs text-secondary d-block">Gambar saat ini</span>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 text-end">
                                <h6 class="mb-0">Total: <span id="total-display-masuk">Rp {{ number_format($masuk->total, 0, ',', '.') }}</span></h6>
                                <input type="hidden" name="729_total" id="total-hidden-masuk" value="{{ $masuk->total }}">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label class="form-label">Keterangan</label>
                                <textarea name="729_keterangan" rows="2" class="form-control">{{ old('729_keterangan', $masuk->keterangan) }}</textarea>
                            </div>
                            <div class="col-md-4 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn bg-gradient-success btn-lg w-100">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
            const stokInfo = row.querySelector('.stok-info-masuk');
            const namaHidden = row.querySelector('.nama-barang-hidden-masuk');

            if (e.target.value === 'LAINNYA') {
                namaContainer.style.display = 'block';
                namaInput.disabled = false;
                namaInput.required = true;
                stokInfo.innerText = '';
                if (namaHidden) namaHidden.value = '';
            } else {
                namaContainer.style.display = 'none';
                namaInput.disabled = true;
                namaInput.required = false;
                namaInput.value = '';
                const opt = e.target.selectedOptions[0];
                const stok = opt.getAttribute('data-stok');
                const nama = opt.getAttribute('data-nama');
                stokInfo.innerText = stok ? 'Stok tercatat: ' + stok : '';
                if (namaHidden) namaHidden.value = nama || '';
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
            if (input.type !== 'button' && input.type !== 'file') input.value = '';
        });
        newRow.querySelector('.jumlah-masuk').value = 1;
        newRow.querySelector('.harga-masuk').value = '';
        newRow.querySelector('.pilih-barang-masuk').value = '';
        const namaHidden = newRow.querySelector('.nama-barang-hidden-masuk');
        if (namaHidden) namaHidden.value = '';
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

    hitungTotalMasuk();
</script>
@endsection
