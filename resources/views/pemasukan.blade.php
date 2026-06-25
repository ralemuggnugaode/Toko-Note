@extends('partials.main')
@section('page')
    <div class="row">
  <div class="col-lg-8">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Form Catatan Pemasukan Barang</h6>
      </div>
      <div class="card-body px-4 pt-4 pb-2">
        <form action="#" method="POST" id="formMasuk">
          @csrf
          <div class="row">
            <div class="col-md-4">
              <label class="form-label">Tanggal</label>
              <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Pihak</label>
              <input type="text" name="pihak" class="form-control" placeholder="Supplier A" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Nomor Nota (opsional)</label>
              <input type="text" name="nomor" class="form-control" placeholder="NOTA-001">
            </div>
          </div>
          <hr class="horizontal dark">
          <h6 class="text-uppercase text-body text-xs font-weight-bolder">Daftar Barang</h6>
          <div id="items-container">
            <div class="row item-row mb-3">
              <div class="col-md-5">
                <label class="form-label">Barang</label>
                <select name="barang_id[]" class="form-control pilih-barang" required>
                  <option value="">Pilih barang</option>
                  foreach
                    <option value="id_barang" data-harga="harga_beli">
                      nama barang
                    </option>

                </select>
              </div>
              <div class="col-md-2">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jumlah[]" class="form-control jumlah" min="1" required>
              </div>
              <div class="col-md-3">
                <label class="form-label">Harga Satuan</label>
                <input type="number" name="harga_satuan[]" class="form-control harga-satuan" required>
              </div>
              <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-link text-danger remove-item p-0">
                  <i class="fa fa-times"></i>
                </button>
              </div>
            </div>
          </div>
          <button type="button" id="add-item" class="btn btn-outline-primary btn-sm mt-2">
            <i class="fa-solid fa-plus"></i> Tambah Barang
          </button>
          <hr class="horizontal dark">
          <div class="row align-items-center">
            <div class="col-md-9">
              <label class="form-label">Keterangan</label>
              <textarea name="keterangan" rows="2" class="form-control" placeholder="Catatan tambahan..."></textarea>
            </div>
            <div class="col-md-3 text-end">
              <h6 class="mb-0">Total: <span id="total-display">Rp 0</span></h6>
              <input type="hidden" name="total" id="total-hidden" value="0">
              <button type="submit" class="btn bg-gradient-success btn-lg mt-2">Simpan Catatan Masuk</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header pb-0">
        <h6>5 Catatan Masuk Terakhir</h6>
      </div>
      <div class="card-body p-3">
        <div class="list-group list-group-flush">
          foreach masuk terakhit
          <div class="list-group-item border-0 px-0">
            <div class="d-flex justify-content-between">
              <span class="text-sm font-weight-bold">nomor</span>
              <small class="text-secondary">format</small>
            </div>
            <p class="text-xs mb-0">pihak</p>
            <p class="text-xs text-success font-weight-bold">Rp total</p>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
      const jumlah = row.querySelector('.jumlah').value;
      const harga = row.querySelector('.harga-satuan').value;
      if (jumlah && harga) total += parseInt(jumlah) * parseInt(harga);
    });
    document.getElementById('total-display').innerText = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('total-hidden').value = total;
  }

  document.addEventListener('input', function(e) {
    if (e.target.classList.contains('jumlah') || e.target.classList.contains('harga-satuan')) {
      hitungTotal();
    }
  });

  document.addEventListener('change', function(e) {
    if (e.target.classList.contains('pilih-barang')) {
      const harga = e.target.selectedOptions[0].getAttribute('data-harga');
      const row = e.target.closest('.item-row');
      row.querySelector('.harga-satuan').value = harga || '';
      hitungTotal();
    }
  });

  document.getElementById('add-item').addEventListener('click', function() {
    const container = document.getElementById('items-container');
    const firstRow = container.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    newRow.querySelector('.jumlah').value = 1;
    newRow.querySelector('.harga-satuan').value = '';
    container.appendChild(newRow);
    hitungTotal();
  });

  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
      const row = e.target.closest('.item-row');
      if (document.querySelectorAll('.item-row').length > 1) {
        row.remove();
        hitungTotal();
      }
    }
  });
</script>
@endpush
@endsection
