@extends('pages.partials.main')
@section('page')
<div class="row">
    <!-- FORM UTAMA -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header pb-0"><h6>Form Catatan Keluar</h6></div>
            <div class="card-body px-4 pt-4 pb-2">
                <div id="js-alert-container"></div>

                {{-- Alert Notifikasi Session (Auto-Dismiss 4 Detik) --}}
                @foreach (['success' => '#2dce89', 'error' => '#f5365c'] as $key => $color)
                    @if(session($key))
                        <div class="alert alert-{{ $key == 'success' ? 'success' : 'danger' }} alert-dismissible fade show text-white alert-auto-dismiss mb-3" style="background-color: {{ $color }};">
                            <span><strong>{{ ucfirst($key) }}!</strong> {{ session($key) }}</span>
                            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"><span aria-hidden="true">&times;</span></button>
                        </div>
                    @endif
                @endforeach

                <form action="{{ route('catatan-keluar-742.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4"><label class="form-label">Tanggal</label><input type="date" name="tanggal_742" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-md-4"><label class="form-label">Pihak</label><input type="text" name="pihak_742" class="form-control" placeholder="Customer/Pembeli" required></div>
                        <div class="col-md-4"><label class="form-label">Nomor</label><input type="text" name="nomor_742" class="form-control" placeholder="NOTA-K001"></div>
                    </div>

                    <hr class="horizontal dark">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
                    <div id="items-container-keluar">
                        <div class="row item-row-keluar mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Barang</label>
                                <select name="barangid_742[]" class="form-control pilih-barang-keluar" required>
                                    <option value="">Pilih barang</option>
                                    @foreach($stokBarang as $b)
                                        <option value="{{ $b->id }}" data-harga="{{ $b->{'719_harga_jual'} }}" data-stok="{{ $b->{'719_stok_tercatat'} }}">{{ $b->{'719_nama'} }} - Stok: {{ $b->{'719_stok_tercatat'} }}</option>
                                    @endforeach
                                </select>
                                <small class="text-secondary stok-info"></small>
                            </div>
                            <div class="col-md-2"><label class="form-label">Jumlah</label><input type="number" name="jumlah_742[]" class="form-control jumlah" min="1" required></div>
                            <div class="col-md-3"><label class="form-label">Harga Jual</label><input type="number" name="harga_jual_742[]" class="form-control harga-jual-742" required></div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-link text-danger remove-item p-0"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-item-keluar" class="btn btn-outline-primary btn-sm mt-2"><i class="fa-solid fa-plus"></i> Tambah Barang</button>

                    <hr class="horizontal dark">
                    <div class="row align-items-center">
                        <div class="col-md-5"><label class="form-label">Keterangan</label><textarea name="keterangan_742" rows="2" class="form-control"></textarea></div>
                        <div class="col-md-4">
                            <label class="form-label">Upload Gambar / Nota</label>
                            <input type="file" name="gambar_742" class="form-control input-file-742" accept="image/jpeg,image/png,image/jpg,image/webp">
                            <small class="text-secondary text-xxs">Format: JPG, JPEG, PNG, WEBP (Maks 2MB)</small>
                        </div>
                        <div class="col-md-3 text-end">
                            <h6 class="mb-1">Total: <span id="total-display-keluar" class="text-danger font-weight-bold">Rp 0</span></h6>
                            <input type="hidden" name="total_742" id="total-hidden-keluar" value="0">
                            <button type="submit" class="btn bg-gradient-danger w-100 mt-2">Simpan Catatan Keluar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SIDEBAR DAFTAR TERAKHIR -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header pb-0"><h6>5 Catatan Keluar Terakhir</h6></div>
            <div class="card-body p-3">
                <div class="list-group list-group-flush">
                    @forelse ($keluarTerakhir ?? [] as $index => $keluar)
                        <div class="list-group-item border-0 px-0 d-flex justify-content-between align-items-start">
                            <div style="flex: 1;" class="pe-2">
                                <span class="text-sm font-weight-bold text-dark d-block mb-1">{{ $keluar->nomor_742 ?? '-' }}</span>
                                <p class="text-xs mb-0">Pihak: {{ $keluar->pihak_742 }}</p>
                                <p class="text-xs text-secondary mb-0">
                                    Ket:
                                    @if(strlen($keluar->keterangan_742) > 40)
                                        <span id="short-ket-{{ $index }}">{{ Str::limit($keluar->keterangan_742, 40) }}</span>
                                        <span id="full-ket-{{ $index }}" style="display:none;">{{ $keluar->keterangan_742 }}</span>
                                        <button type="button" class="btn btn-link p-0 text-xs text-info btn-toggle-ket" data-index="{{ $index }}">BACA SELENGKAPNYA</button>
                                    @else {{ $keluar->keterangan_742 ?? '-' }} @endif
                                </p>
                                <p class="text-xs text-danger font-weight-bold mt-1 mb-0">Rp {{ number_format($keluar->total_742, 0, ',', '.') }}</p>
                                <small class="text-secondary text-xxs">{{ date('d/m/Y', strtotime($keluar->tanggal_742)) }}</small>
                            </div>

                            <div class="d-flex align-items-center align-self-start mt-1">
                                @if(!empty($keluar->gambar_742))
                                    <div class="me-3">
                                        <a href="javascript:void(0);" class="btn-preview-image-742" data-src="{{ asset('uploads/catatan_keluar/' . $keluar->gambar_742) }}" data-nomor="{{ $keluar->nomor_742 ?? 'Nota' }}">
                                            <div style="width: 48px; height: 48px; border-radius: 50%; overflow: hidden; border: 2px solid #e9ecef;">
                                                <img src="{{ asset('uploads/catatan_keluar/' . $keluar->gambar_742) }}" style="width:100%; height:100%; object-fit:cover;">
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                <div class="d-flex flex-column align-items-end">
                                    <button class="btn btn-link text-primary p-0 mb-1 btn-edit-742 text-xs font-weight-bold"
                                        data-id="{{ $keluar->id }}" data-tanggal="{{ $keluar->tanggal_742 }}" data-pihak="{{ $keluar->pihak_742 }}" data-nomor="{{ $keluar->nomor_742 }}" data-keterangan="{{ $keluar->keterangan_742 }}" data-items="{{ $keluar->items_742 }}" data-gambar="{{ $keluar->gambar_742 ? asset('uploads/catatan_keluar/' . $keluar->gambar_742) : '' }}">
                                        <i class="fa fa-pencil text-xxs"></i> EDIT
                                    </button>
                                    <button class="btn btn-link text-danger p-0 mb-0 btn-delete-742 text-xs font-weight-bold" data-id="{{ $keluar->id }}" data-nomor="{{ $keluar->nomor_742 ?? '-' }}">
                                        <i class="fa fa-trash text-xxs"></i> HAPUS
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-secondary text-xs">Belum ada catatan keluar terbaru</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL POP-UPS ================= -->

<!-- 1. MODAL EDIT -->
<div class="modal fade" id="modalEdit742" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Catatan Keluar</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit742" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div id="modal-edit-alert-container"></div>
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Tanggal</label><input type="date" name="tanggal_742" id="edit_tanggal" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Pihak</label><input type="text" name="pihak_742" id="edit_pihak" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Nomor</label><input type="text" name="nomor_742" id="edit_nomor" class="form-control"></div>
                    </div>
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
                    <div id="edit-items-container"></div>
                    <button type="button" id="edit-add-item" class="btn btn-outline-primary btn-sm mt-2"><i class="fa-solid fa-plus"></i> Tambah Barang</button>
                    <hr class="horizontal dark">
                    <div class="row align-items-center">
                        <div class="col-md-6"><label class="form-label">Keterangan</label><textarea name="keterangan_742" id="edit_keterangan" rows="2" class="form-control"></textarea></div>
                        <div class="col-md-6">
                            <label class="form-label">Ganti Gambar / Nota</label>
                            <div class="d-flex align-items-center">
                                <div id="edit_preview_container" class="me-3" style="display:none;">
                                    <img id="edit_preview_img" src="" style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
                                </div>
                                <input type="file" name="gambar_742" class="form-control input-file-742" accept="image/jpeg,image/png,image/jpg,image/webp">
                            </div>
                            <small class="text-secondary text-xxs">Format: JPG, JPEG, PNG, WEBP (Maks 2MB)</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <h5 class="m-0">Total: <span id="edit-total-display" class="text-danger font-weight-bold">Rp 0</span></h5>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn bg-gradient-danger">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 2. MODAL PREVIEW GAMBAR -->
<div class="modal fade" id="modalImagePreview742" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pratinjau Gambar <span id="preview_image_title"></span></h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="preview_modal_src" src="" class="img-fluid rounded" style="max-height: 70vh; object-fit: contain;">
            </div>
            <div class="modal-footer">
                <a id="download_image_btn" href="" download class="btn btn-outline-primary btn-sm mb-0"><i class="fa fa-download me-1"></i> Unduh</a>
                <button type="button" class="btn btn-secondary btn-sm mb-0" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. MODAL HAPUS -->
<div class="modal fade" id="modalDelete742" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fa fa-exclamation-circle text-danger mb-3" style="font-size: 3rem;"></i>
                <h6 class="text-dark">Yakin ingin menghapus data <strong id="delete_nomor_text"></strong>?</h6>
                <p class="text-xs text-secondary mb-0 mt-2">Stok barang akan otomatis dikembalikan ke gudang.</p>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                <form id="formDelete742" method="POST" class="d-inline mb-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn bg-gradient-danger">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- TEMPLATE ROW BARANG (EDIT) -->
<div id="item-row-template" style="display: none;">
    <div class="row item-row-edit mb-3">
        <div class="col-md-4">
            <select name="barangid_742[]" class="form-control pilih-barang-edit" required>
                <option value="">Pilih barang</option>
                @foreach($stokBarang as $b)
                    <option value="{{ $b->id }}" data-harga="{{ $b->{'719_harga_jual'} }}" data-stok="{{ $b->{'719_stok_tercatat'} }}">{{ $b->{'719_nama'} }} - Stok: {{ $b->{'719_stok_tercatat'} }}</option>
                @endforeach
            </select>
            <small class="text-secondary stok-info"></small>
        </div>
        <div class="col-md-2"><input type="number" name="jumlah_742[]" class="form-control jumlah" min="1" required></div>
        <div class="col-md-4"><input type="number" name="harga_jual_742[]" class="form-control harga-jual-edit" required></div>
        <div class="col-md-2 d-flex align-items-center">
            <button type="button" class="btn btn-link text-danger remove-item-edit p-0"><i class="fa fa-times"></i></button>
        </div>
    </div>
</div>

<!-- ================= JAVASCRIPT ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Auto Dismiss Alert bawaan Session Laravel (4 Detik)
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-dismiss').forEach(el => {
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const alert = bootstrap.Alert.getOrCreateInstance(el);
                if (alert) alert.close();
            } else {
                el.classList.remove('show');
                setTimeout(() => el.remove(), 150);
            }
        });
    }, 4000);

    // Helper Dynamic Alert Banner (Tampil & Otomatis Hilang dalam 4 Detik)
    function showAlert(containerId, msg, type = 'danger') {
        const c = document.getElementById(containerId);
        if (!c) return;

        const isSuccess = type === 'success';
        const bgColor = isSuccess ? '#2dce89' : '#f5365c';
        const title = isSuccess ? 'Berhasil!' : 'Gagal!';

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${isSuccess ? 'success' : 'danger'} alert-dismissible fade show text-white mb-3`;
        alertDiv.style.backgroundColor = bgColor;
        alertDiv.innerHTML = `
            <span><strong>${title}</strong> ${msg}</span>
            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert">&times;</button>
        `;

        c.innerHTML = '';
        c.appendChild(alertDiv);

        // Timer Otomatis Hilang (4 Detik / 4000ms)
        setTimeout(() => {
            if (alertDiv && document.body.contains(alertDiv)) {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                    if (bsAlert) bsAlert.close();
                } else {
                    alertDiv.classList.remove('show');
                    setTimeout(() => alertDiv.remove(), 150);
                }
            }
        }, 4000);
    }

    // Validasi Format & Ukuran File Gambar
    document.addEventListener('change', function (e) {
        if (e.target.matches('.input-file-742, [name="gambar_742"]')) {
            const file = e.target.files[0];
            if (!file) return;

            const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            const maxSize = 2 * 1024 * 1024; // 2MB

            const isModal = e.target.closest('#modalEdit742');
            const containerId = isModal ? 'modal-edit-alert-container' : 'js-alert-container';

            if (!allowedExtensions.includes(fileExtension)) {
                showAlert(containerId, `Format file '${fileExtension.toUpperCase()}' tidak didukung! Gunakan JPG, JPEG, PNG, atau WEBP.`, 'danger');
                e.target.value = '';
                return;
            }

            if (file.size > maxSize) {
                showAlert(containerId, `Ukuran file (${(file.size / 1048576).toFixed(2)} MB) melebihi batas maksimal 2MB.`, 'danger');
                e.target.value = '';
                return;
            }
        }
    });

    // Event Delegation (Handling Tombol Klik)
    document.addEventListener('click', function(e) {
        // Toggle Baca Selengkapnya
        if (e.target.classList.contains('btn-toggle-ket')) {
            const idx = e.target.dataset.index;
            const s = document.getElementById('short-ket-' + idx), f = document.getElementById('full-ket-' + idx);
            f.style.display = f.style.display === 'none' ? 'inline' : 'none';
            s.style.display = s.style.display === 'none' ? 'inline' : 'none';
            e.target.innerText = f.style.display === 'inline' ? 'SEMBUNYIKAN' : 'BACA SELENGKAPNYA';
        }

        // Modal Preview Gambar
        const btnImg = e.target.closest('.btn-preview-image-742');
        if (btnImg) {
            document.getElementById('preview_modal_src').src = btnImg.dataset.src;
            document.getElementById('download_image_btn').href = btnImg.dataset.src;
            document.getElementById('preview_image_title').innerText = btnImg.dataset.nomor !== '-' ? `(${btnImg.dataset.nomor})` : '';
            new bootstrap.Modal(document.getElementById('modalImagePreview742')).show();
        }

        // Modal Hapus Data
        const btnDel = e.target.closest('.btn-delete-742');
        if (btnDel) {
            document.getElementById('formDelete742').action = `/catatan-keluar-742/${btnDel.dataset.id}`;
            document.getElementById('delete_nomor_text').innerText = btnDel.dataset.nomor !== '-' ? `(${btnDel.dataset.nomor})` : '';
            new bootstrap.Modal(document.getElementById('modalDelete742')).show();
        }

        // Modal Edit Data
        const btnEdit = e.target.closest('.btn-edit-742');
        if (btnEdit) {
            const d = btnEdit.dataset;
            document.getElementById('formEdit742').action = `/catatan-keluar-742/${d.id}`;
            document.getElementById('edit_tanggal').value = d.tanggal;
            document.getElementById('edit_pihak').value = d.pihak;
            document.getElementById('edit_nomor').value = d.nomor;
            document.getElementById('edit_keterangan').value = d.keterangan;

            const prevCont = document.getElementById('edit_preview_container');
            if (d.gambar) {
                document.getElementById('edit_preview_img').src = d.gambar;
                prevCont.style.display = 'block';
            } else prevCont.style.display = 'none';

            const container = document.getElementById('edit-items-container');
            container.innerHTML = '';
            const items = JSON.parse(d.items || '[]');
            items.forEach(item => {
                const row = document.querySelector('#item-row-template .item-row-edit').cloneNode(true);
                const sel = row.querySelector('.pilih-barang-edit');
                sel.value = item.barang_id;
                row.querySelector('.jumlah').value = item.jumlah;
                row.querySelector('.harga-jual-edit').value = item.harga;
                row.querySelector('.stok-info').innerText = 'Stok: ' + (sel.selectedOptions[0]?.dataset.stok || 0);
                container.appendChild(row);
            });
            hitungTotal('.item-row-edit', '.harga-jual-edit', '#edit-total-display');
            new bootstrap.Modal(document.getElementById('modalEdit742')).show();
        }

        // Remove Item Row
        if (e.target.closest('.remove-item') && document.querySelectorAll('.item-row-keluar').length > 1) {
            e.target.closest('.item-row-keluar').remove();
            hitungTotal('.item-row-keluar', '.harga-jual-742', '#total-display-keluar', '#total-hidden-keluar');
        }
        if (e.target.closest('.remove-item-edit') && document.querySelectorAll('.item-row-edit').length > 1) {
            e.target.closest('.item-row-edit').remove();
            hitungTotal('.item-row-edit', '.harga-jual-edit', '#edit-total-display');
        }
    });

    // Logika Hitung Total
    function hitungTotal(rowClass, hargaClass, displayId, hiddenId = null) {
        let total = 0;
        document.querySelectorAll(rowClass).forEach(row => {
            const j = parseInt(row.querySelector('.jumlah').value) || 0;
            const h = parseInt(row.querySelector(hargaClass).value) || 0;
            total += j * h;
        });
        document.querySelector(displayId).innerText = 'Rp ' + total.toLocaleString('id-ID');
        if(hiddenId) document.querySelector(hiddenId).value = total;
    }

    // Auto-Calculate di Input
    document.addEventListener('input', function(e) {
        if (e.target.closest('.item-row-keluar')) hitungTotal('.item-row-keluar', '.harga-jual-742', '#total-display-keluar', '#total-hidden-keluar');
        if (e.target.closest('.item-row-edit')) hitungTotal('.item-row-edit', '.harga-jual-edit', '#edit-total-display');
    });

    // Auto-Select Barang
    document.addEventListener('change', function(e) {
        if (e.target.matches('.pilih-barang-keluar, .pilih-barang-edit')) {
            const opt = e.target.selectedOptions[0];
            const row = e.target.closest('.row');
            const isEdit = e.target.classList.contains('pilih-barang-edit');

            row.querySelector(isEdit ? '.harga-jual-edit' : '.harga-jual-742').value = opt.dataset.harga || '';
            row.querySelector('.stok-info').innerText = opt.dataset.stok ? 'Stok: ' + opt.dataset.stok : '';

            isEdit ? hitungTotal('.item-row-edit', '.harga-jual-edit', '#edit-total-display')
                   : hitungTotal('.item-row-keluar', '.harga-jual-742', '#total-display-keluar', '#total-hidden-keluar');
        }
    });

    // Tambah Baris Barang Dynamic
    document.getElementById('add-item-keluar').addEventListener('click', function() {
        const c = document.getElementById('items-container-keluar');
        const newRow = c.querySelector('.item-row-keluar').cloneNode(true);
        newRow.querySelectorAll('input').forEach(i => i.value = '');
        newRow.querySelector('.jumlah').value = 1;
        newRow.querySelector('.stok-info').innerText = '';
        c.appendChild(newRow);
    });

    document.getElementById('edit-add-item').addEventListener('click', function() {
        const c = document.getElementById('edit-items-container');
        const newRow = document.querySelector('#item-row-template .item-row-edit').cloneNode(true);
        newRow.querySelectorAll('input').forEach(i => i.value = '');
        newRow.querySelector('.jumlah').value = 1;
        c.appendChild(newRow);
    });
});
</script>
@endsection
