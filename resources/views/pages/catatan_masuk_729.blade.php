@extends('pages.partials.main')

@section('page')

<div class="container-fluid py-2">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-text"><strong>Berhasil!</strong> {{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span class="alert-text">
                <strong>Gagal disimpan!</strong> Periksa kembali isian berikut:
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Kolom Kiri: Form -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Catatan Masuk</h6>
                </div>
                <div class="card-body px-4 pt-4 pb-2">
                    <form action="{{ route('page.catatan-masuk-729.store') }}" method="POST" id="formMasuk" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="729_tanggal" class="form-control" value="{{ old('729_tanggal', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pihak</label>
                                <input type="text" name="729_pihak" class="form-control" placeholder="Supplier" value="{{ old('729_pihak') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nomor (opsional)</label>
                                <input type="text" name="729_nomor" class="form-control" placeholder="NOTA-M001" value="{{ old('729_nomor') }}">
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
                        <div id="items-container-masuk">
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
                        </div>
                        <button type="button" id="add-item-masuk" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fa-solid fa-plus"></i> Tambah Barang
                        </button>
                        <hr class="horizontal dark">

                        <!-- Bagian Total, Gambar, Keterangan -->
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Gambar (opsional)</label>
                                <input type="file" name="729_gambar" class="form-control-file" accept="image/*">
                                <small class="text-secondary">Max 5MB, format jpg/png/jpeg/gif</small>
                            </div>
                            <div class="col-md-6 text-end">
                                <h6 class="mb-0">Total: <span id="total-display-masuk">Rp 0</span></h6>
                                <input type="hidden" name="729_total" id="total-hidden-masuk" value="0">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-8">
                                <label class="form-label">Keterangan</label>
                                <textarea name="729_keterangan" rows="2" class="form-control">{{ old('729_keterangan') }}</textarea>
                            </div>
                            <div class="col-md-4 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn bg-gradient-success btn-lg w-100">Simpan Catatan Masuk</button>
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
                        @if($masukTerakhir->isEmpty())
                            <p class="text-sm text-center text-secondary">Belum ada catatan masuk</p>
                        @else
                            @foreach($masukTerakhir as $masuk)
                                <div class="list-group-item border-0 px-0">
                                    <div class="d-flex">
                                        @if($masuk->gambar)
                                            <div class="me-3" style="cursor:pointer;" onclick="openImageModal('{{ asset('storage/' . $masuk->gambar) }}')">
                                                <img src="{{ asset('storage/' . $masuk->gambar) }}" alt="Gambar" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-sm font-weight-bold">{{ $masuk->nomor }}</span>
                                                <small class="text-secondary">{{ $masuk->tanggal->format('d/m/Y') }}</small>
                                            </div>
                                            <p class="text-xs mb-1">{{ $masuk->pihak }}</p>
                                            <div class="d-flex justify-content-between">
                                                <p class="text-xs text-success font-weight-bold mb-0">Rp {{ number_format($masuk->total, 0, ',', '.') }}</p>
                                                @if(!empty($masuk->keterangan))
                                                    <p class="text-xs mb-0 text-secondary">
                                                        <i class="fa fa-pencil-alt me-1"></i>
                                                        {{ $masuk->keterangan }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="d-flex justify-content-end mt-1">
                                                <a href="{{ route('page.catatan-masuk-729.edit', $masuk->id) }}" class="btn btn-link text-dark px-2 py-0 mb-0 text-xs">
                                                    <i class="fa fa-pencil-alt"></i> Edit
                                                </a>
                                                <form action="{{ route('page.catatan-masuk-729.destroy', $masuk->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan ini? Stok barang terkait akan dikurangi kembali.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger px-2 py-0 mb-0 text-xs">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gambar Catatan Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Gambar" style="max-width:100%; max-height:80vh;">
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

    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
        myModal.show();
    }

    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('pilih-barang-masuk')) {
            const row = e.target.closest('.item-row-masuk');
            const namaContainer = row.querySelector('.nama-lain-container');
            const namaInput = row.querySelector('.nama-barang-lain');
            const hargaInput = row.querySelector('.harga-masuk');
            const stokInfo = row.querySelector('.stok-info-masuk');
            const namaHidden = row.querySelector('.nama-barang-hidden-masuk');

            if (e.target.value === 'LAINNYA') {
                namaContainer.style.display = 'block';
                namaInput.disabled = false;
                namaInput.required = true;
                hargaInput.value = '';
                hargaInput.required = true;
                stokInfo.innerText = '';
                if (namaHidden) namaHidden.value = '';
            } else {
                namaContainer.style.display = 'none';
                namaInput.disabled = true;
                namaInput.required = false;
                namaInput.value = '';
                const opt = e.target.selectedOptions[0];
                const harga = opt.getAttribute('data-harga');
                const stok = opt.getAttribute('data-stok');
                const nama = opt.getAttribute('data-nama');
                hargaInput.value = harga || '';
                hargaInput.required = true;
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
@endsection
