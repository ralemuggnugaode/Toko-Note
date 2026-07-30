@extends('pages.partials.main')
@section('page')
<div class="row">
    <!-- FORM UTAMA -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header pb-0"><h6>Form Catatan Keluar</h6></div>
            <div class="card-body px-4 pt-4 pb-2">
                <div id="js-alert-container"></div>

                @foreach (['success' => '#2dce89', 'error' => '#f5365c'] as $key => $color)
                    @if(session($key))
                        <div class="alert alert-{{ $key == 'success' ? 'success' : 'danger' }} alert-dismissible fade show text-white alert-auto-dismiss mb-3" style="background-color: {{ $color }};">
                            <span><strong>{{ ucfirst($key) }}!</strong> {{ session($key) }}</span>
                            <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert">&times;</button>
                        </div>
                    @endif
                @endforeach

                <form action="{{ route('page.catatan-keluar-742.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4"><label class="form-label">Tanggal</label><input type="date" name="tanggal_742" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                        <div class="col-md-4"><label class="form-label">Pihak</label><input type="text" name="pihak_742" class="form-control" placeholder="Customer/Pembeli" required></div>
                        <div class="col-md-4"><label class="form-label">Nomor</label><input type="text" name="nomor_742" class="form-control" placeholder="NOTA-K001"></div>
                    </div>

                    <hr class="horizontal dark">
                    <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
                    <div id="items-container-keluar">
                        <div class="row item-row mb-3" data-type="keluar">
                            <div class="col-md-4">
                                <label class="form-label">Barang</label>
                                <select name="barangid_742[]" class="form-control pilih-barang" required>
                                    <option value="">Pilih barang</option>
                                    @foreach($stokBarang as $b)
                                        <option value="{{ $b->id }}" data-harga="{{ $b->{'719_harga_jual'} }}" data-stok="{{ $b->{'719_stok_tercatat'} }}">{{ $b->{'719_nama'} }} - Stok: {{ $b->{'719_stok_tercatat'} }}</option>
                                    @endforeach
                                </select>
                                <small class="text-secondary stok-info"></small>
                            </div>
                            <div class="col-md-2"><label class="form-label">Jumlah</label><input type="number" name="jumlah_742[]" class="form-control jumlah" min="1" value="1" required></div>
                            <div class="col-md-4"><label class="form-label">Harga Jual</label><input type="number" name="harga_jual_742[]" class="form-control harga-jual" required></div>
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
                            <input type="file" name="gambar_742" class="form-control input-file-742" accept="image/*">
                            <small class="text-secondary text-xxs">Format: JPG, PNG, WEBP (Maks 2MB)</small>
                        </div>
                        <div class="col-md-3 text-end">
                            <h6 class="mb-1">Total: <span id="total-display-keluar" class="text-danger font-weight-bold">Rp 0</span></h6>
                            <input type="hidden" name="total_742" id="total-hidden-keluar" value="0">
                            <button type="submit" class="btn bg-gradient-danger w-100 mt-2">Simpan Catatan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SIDEBAR -->
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
                                    <div class="me-2">
                                        <a href="javascript:void(0);" class="btn-preview-image-742" data-src="{{ asset('uploads/catatan_keluar/' . $keluar->gambar_742) }}" data-nomor="{{ $keluar->nomor_742 ?? 'Nota' }}">
                                            <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; border: 2px solid #e9ecef;">
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

<!-- MODALS -->
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
                                    <img id="edit_preview_img" src="" style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
                                </div>
                                <input type="file" name="gambar_742" class="form-control input-file-742" accept="image/*">
                            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Auto Dismiss Alert
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-dismiss').forEach(el => bootstrap.Alert.getOrCreateInstance(el)?.close());
    }, 4000);

    function showAlert(containerId, msg, type = 'danger') {
        const c = document.getElementById(containerId);
        if (!c) return;
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show text-white mb-3`;
        alertDiv.style.backgroundColor = type === 'success' ? '#2dce89' : '#f5365c';
        alertDiv.innerHTML = `<span><strong>${type === 'success' ? 'Berhasil!' : 'Gagal!'}</strong> ${msg}</span><button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert">&times;</button>`;
        c.replaceChildren(alertDiv);
        setTimeout(() => alertDiv.remove(), 4000);
    }

    // Kalkulasi Total Generik
    function hitungTotal(containerSelector, displaySelector, hiddenSelector = null) {
        let total = 0;
        document.querySelectorAll(`${containerSelector} .item-row`).forEach(row => {
            const j = parseInt(row.querySelector('.jumlah')?.value) || 0;
            const h = parseInt(row.querySelector('.harga-jual')?.value) || 0;
            total += j * h;
        });
        document.querySelector(displaySelector).innerText = 'Rp ' + total.toLocaleString('id-ID');
        if (hiddenSelector) document.querySelector(hiddenSelector).value = total;
    }

    // Event Delegation Utama
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-toggle-ket')) {
            const idx = e.target.dataset.index;
            const s = document.getElementById('short-ket-' + idx), f = document.getElementById('full-ket-' + idx);
            const isHidden = f.style.display === 'none';
            f.style.display = isHidden ? 'inline' : 'none';
            s.style.display = isHidden ? 'none' : 'inline';
            e.target.innerText = isHidden ? 'SEMBUNYIKAN' : 'BACA SELENGKAPNYA';
        }

        const btnImg = e.target.closest('.btn-preview-image-742');
        if (btnImg) {
            document.getElementById('preview_modal_src').src = btnImg.dataset.src;
            document.getElementById('download_image_btn').href = btnImg.dataset.src;
            document.getElementById('preview_image_title').innerText = btnImg.dataset.nomor !== '-' ? `(${btnImg.dataset.nomor})` : '';
            new bootstrap.Modal(document.getElementById('modalImagePreview742')).show();
        }

        const btnDel = e.target.closest('.btn-delete-742');
        if (btnDel) {
            document.getElementById('formDelete742').action = `/page.catatan-keluar-742/${btnDel.dataset.id}`;
            document.getElementById('delete_nomor_text').innerText = btnDel.dataset.nomor !== '-' ? `(${btnDel.dataset.nomor})` : '';
            new bootstrap.Modal(document.getElementById('modalDelete742')).show();
        }

        const btnEdit = e.target.closest('.btn-edit-742');
        if (btnEdit) {
            const d = btnEdit.dataset;
            document.getElementById('formEdit742').action = `/page.catatan-keluar-742/${d.id}`;
            document.getElementById('edit_tanggal').value = d.tanggal;
            document.getElementById('edit_pihak').value = d.pihak;
            document.getElementById('edit_nomor').value = d.nomor;
            document.getElementById('edit_keterangan').value = d.keterangan;

            const prevCont = document.getElementById('edit_preview_container');
            prevCont.style.display = d.gambar ? 'block' : 'none';
            if(d.gambar) document.getElementById('edit_preview_img').src = d.gambar;

            const container = document.getElementById('edit-items-container');
            container.innerHTML = '';
            JSON.parse(d.items || '[]').forEach(item => {
                const newRow = createRow();
                newRow.querySelector('.pilih-barang').value = item.barang_id;
                newRow.querySelector('.jumlah').value = item.jumlah;
                newRow.querySelector('.harga-jual').value = item.harga;
                const opt = newRow.querySelector('.pilih-barang').selectedOptions[0];
                newRow.querySelector('.stok-info').innerText = opt?.dataset.stok ? 'Stok: ' + opt.dataset.stok : '';
                container.appendChild(newRow);
            });
            hitungTotal('#edit-items-container', '#edit-total-display');
            new bootstrap.Modal(document.getElementById('modalEdit742')).show();
        }

        if (e.target.closest('.remove-item')) {
            const container = e.target.closest('#items-container-keluar, #edit-items-container');
            if (container.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
                if(container.id === 'items-container-keluar') hitungTotal('#items-container-keluar', '#total-display-keluar', '#total-hidden-keluar');
                else hitungTotal('#edit-items-container', '#edit-total-display');
            }
        }
    });

    // Buat Elemen Row Dinamis
    function createRow() {
        const firstRow = document.querySelector('.item-row');
        const clone = firstRow.cloneNode(true);
        clone.querySelectorAll('input').forEach(i => i.value = '');
        clone.querySelector('.jumlah').value = 1;
        clone.querySelector('.stok-info').innerText = '';
        return clone;
    }

    // Handlers Tambah Barang
    document.getElementById('add-item-keluar').addEventListener('click', () => {
        document.getElementById('items-container-keluar').appendChild(createRow());
    });
    document.getElementById('edit-add-item').addEventListener('click', () => {
        document.getElementById('edit-items-container').appendChild(createRow());
    });

    // Auto-Calculate & Change
    document.addEventListener('input', (e) => {
        if (e.target.closest('#items-container-keluar')) hitungTotal('#items-container-keluar', '#total-display-keluar', '#total-hidden-keluar');
        if (e.target.closest('#edit-items-container')) hitungTotal('#edit-items-container', '#edit-total-display');
    });

    document.addEventListener('change', (e) => {
        if (e.target.matches('.pilih-barang')) {
            const opt = e.target.selectedOptions[0];
            const row = e.target.closest('.item-row');
            row.querySelector('.harga-jual').value = opt.dataset.harga || '';
            row.querySelector('.stok-info').innerText = opt.dataset.stok ? 'Stok: ' + opt.dataset.stok : '';

            if (row.closest('#items-container-keluar')) hitungTotal('#items-container-keluar', '#total-display-keluar', '#total-hidden-keluar');
            else hitungTotal('#edit-items-container', '#edit-total-display');
        }

        if (e.target.matches('.input-file-742')) {
            const file = e.target.files[0];
            if (!file) return;
            const containerId = e.target.closest('#modalEdit742') ? 'modal-edit-alert-container' : 'js-alert-container';
            if (!['jpg', 'jpeg', 'png', 'webp'].includes(file.name.split('.').pop().toLowerCase())) {
                showAlert(containerId, 'Format file tidak valid!', 'danger');
                e.target.value = '';
            } else if (file.size > 2 * 1024 * 1024) {
                showAlert(containerId, 'Ukuran file maks 2MB!', 'danger');
                e.target.value = '';
            }
        }
    });
});
</script>
@endsection
