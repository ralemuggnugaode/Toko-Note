    @extends('partials.main')
    @section('page')
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Form Catatan Keluar</h6>
                    </div>
                    <div class="card-body px-4 pt-4 pb-2">
                        <form action="#" method="POST" id="formKeluar">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Pihak</label>
                                    <input type="text" name="pihak" class="form-control" placeholder="Customer/Pembeli"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Nomor (opsional)</label>
                                    <input type="text" name="nomor" class="form-control" placeholder="NOTA-K001">
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
                            <div id="items-container-keluar">
                                <div class="row item-row-keluar mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Barang</label>
                                        <select name="barang_id[]" class="form-control pilih-barang-keluar" required>
                                            <option value="">Pilih barang</option>
                                            foreach barang
                                            <option value="id barang" data-harga="harga-jual" data-stok="stok tercatat">
                                                nama barang - stok tercatat
                                            </option>

                                        </select>
                                        <small class="text-secondary stok-info"></small>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="jumlah[]" class="form-control jumlah" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Harga Jual</label>
                                        <input type="number" name="harga_satuan[]" class="form-control harga-satuan" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-link text-danger remove-item p-0">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-item-keluar" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fa-solid fa-plus"></i> Tambah Barang
                            </button>
                            <hr class="horizontal dark">
                            <div class="row align-items-center">
                                <div class="col-md-9">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" rows="2" class="form-control"></textarea>
                                </div>
                                <div class="col-md-3 text-end">
                                    <h6 class="mb-0">Total: <span id="total-display-keluar">Rp 0</span></h6>
                                    <input type="hidden" name="total" id="total-hidden-keluar" value="0">
                                    <button type="submit" class="btn bg-gradient-danger btn-lg mt-2">Simpan Catatan
                                        Keluar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>5 Catatan Keluar Terakhir</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="list-group list-group-flush">
                            foreach $keluarTerakhir
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between">
                                    <span class="text-sm font-weight-bold">nomor </span>
                                    <small class="text-secondary">format('d/m/Y')</small>
                                </div>
                                <p class="text-xs mb-0">pihak</p>
                                <p class="text-xs text-danger font-weight-bold">Rp total</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function hitungTotalKeluar() {
                    let total = 0;
                    document.querySelectorAll('.item-row-keluar').forEach(row => {
                        const jumlah = parseInt(row.querySelector('.jumlah').value) || 0;
                        const harga = parseInt(row.querySelector('.harga-satuan').value) || 0;
                        total += jumlah * harga;
                    });
                    document.getElementById('total-display-keluar').innerText = 'Rp ' + total.toLocaleString('id-ID');
                    document.getElementById('total-hidden-keluar').value = total;
                }

                document.addEventListener('input', function (e) {
                    if (e.target.closest('.item-row-keluar') && (e.target.classList.contains('jumlah') || e.target.classList.contains('harga-satuan'))) {
                        hitungTotalKeluar();
                    }
                });

                document.addEventListener('change', function (e) {
                    if (e.target.classList.contains('pilih-barang-keluar')) {
                        const opt = e.target.selectedOptions[0];
                        const harga = opt.getAttribute('data-harga');
                        const stok = opt.getAttribute('data-stok');
                        const row = e.target.closest('.item-row-keluar');
                        row.querySelector('.harga-satuan').value = harga || '';
                        row.querySelector('.stok-info').innerText = stok ? 'Stok tercatat: ' + stok : '';
                        hitungTotalKeluar();
                    }
                });

                document.getElementById('add-item-keluar').addEventListener('click', function () {
                    const container = document.getElementById('items-container-keluar');
                    const firstRow = container.querySelector('.item-row-keluar');
                    const newRow = firstRow.cloneNode(true);
                    newRow.querySelectorAll('input').forEach(input => input.value = '');
                    newRow.querySelector('.jumlah').value = 1;
                    newRow.querySelector('.harga-satuan').value = '';
                    newRow.querySelector('.stok-info').innerText = '';
                    container.appendChild(newRow);
                    hitungTotalKeluar();
                });

                document.addEventListener('click', function (e) {
                    if (e.target.closest('.remove-item')) {
                        const row = e.target.closest('.item-row-keluar');
                        if (document.querySelectorAll('.item-row-keluar').length > 1) {
                            row.remove();
                            hitungTotalKeluar();
                        }
                    }
                });
            </script>
        @endpush
    @endsection
