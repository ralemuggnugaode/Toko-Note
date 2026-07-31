<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran_742;
use App\Models\StokBarang_719;
use App\Traits\ResolvesImageFolder;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CatatanKeluarController extends Controller
{
    use ResolvesImageFolder, LogActivity;

    public function index() //ambil data dan stok transaksi
    {
        return view('pages.catatanKeluar_742', [
            'title'          => 'Catatan Barang Keluar',
            'stokBarang'     => StokBarang_719::all(),
            'keluarTerakhir' => Pengeluaran_742::latest()->take(5)->get()
        ]);
    }

    public function store(Request $request)
    {
        $this->validateInput($request);
        // cek stok
        foreach ($request->barangid_742 as $i => $id) {
            $barang = StokBarang_719::find($id);
            if (!$barang) return back()->withInput()->with('error', 'Barang tidak ditemukan.');

            $stok = (int) $barang->{'719_stok_tercatat'};
            $minta = (int) $request->jumlah_742[$i];
            if ($minta > $stok) {
                return back()->withInput()->with('error', "Stok tidak mencukupi! '{$barang->{'719_nama_barang'}}' sisa {$stok} pcs.");
            }
        }
        // save & potong
        DB::transaction(function () use ($request) {
            [$items, $total] = $this->processItemsAndStock($request->barangid_742, $request->jumlah_742, $request->harga_jual_742, 'decrement');

            $catatan = Pengeluaran_742::create([
                'barangid_742'   => $request->barangid_742[0],
                'tanggal_742'    => $request->tanggal_742,
                'pihak_742'      => $request->pihak_742,
                'nomor_742'      => $request->nomor_742 ?? '-',
                'keterangan_742' => $request->keterangan_742,
                'gambar_742'     => $this->handleUpload($request),
                'items_742'      => json_encode($items),
                'total_742'      => $total,
            ]);

            $this->logActivity($catatan, 'create', $request->all());
        });

        return back()->with('success', 'Catatan pengeluaran barang sukses ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $catatan = Pengeluaran_742::findOrFail($id);
        $this->validateInput($request);

        $oldItems = json_decode($catatan->items_742, true) ?? [];
        foreach ($request->barangid_742 as $i => $bId) {
            $barang = StokBarang_719::find($bId);
            if (!$barang) return back()->with('error', 'Barang tidak ditemukan.');

            $oldQty = collect($oldItems)->firstWhere('barang_id', $bId)['jumlah'] ?? 0;
            $stokTersedia = (int) $barang->{'719_stok_tercatat'} + $oldQty;

            if ((int)$request->jumlah_742[$i] > $stokTersedia) {
                return back()->with('error', "Gagal Update! Stok '{$barang->{'719_nama_barang'}}' maksimal {$stokTersedia} pcs.");
            }
        }
        //restore stok lama, lalu potong stok baru, akan update
        DB::transaction(function () use ($request, $catatan, $oldItems) {
            $this->restoreStock($oldItems);
            [$items, $total] = $this->processItemsAndStock($request->barangid_742, $request->jumlah_742, $request->harga_jual_742, 'decrement');

            $catatan->update([
                'barangid_742'   => $request->barangid_742[0],
                'tanggal_742'    => $request->tanggal_742,
                'pihak_742'      => $request->pihak_742,
                'nomor_742'      => $request->nomor_742 ?? '-',
                'keterangan_742' => $request->keterangan_742,
                'gambar_742'     => $this->handleUpload($request, $catatan->gambar_742),
                'items_742'      => json_encode($items),
                'total_742'      => $total,
            ]);

            $this->logActivity($catatan, 'update', $request->all());
        });

        return back()->with('success', 'Catatan pengeluaran barang berhasil diperbarui!');
    }

    public function destroy($id) //menghapus data lalu mengembalikan stok ke gudang
    {
        $catatan = Pengeluaran_742::findOrFail($id);

        DB::transaction(function () use ($catatan) {
            $this->restoreStock(json_decode($catatan->items_742, true) ?? []);
            if ($catatan->gambar_742) $this->deleteFile($catatan->gambar_742);
            $catatan->delete();

            $this->logActivity($catatan, 'delete');
        });

        return back()->with('success', 'Catatan berhasil dihapus, stok otomatis dikembalikan!');
    }


    private function validateInput(Request $request)
    {
        $request->validate([
            'tanggal_742'     => 'required|date',
            'pihak_742'       => 'required|string|max:255',
            'barangid_742'    => 'required|array',
            'barangid_742.*'  => 'required',
            'jumlah_742'      => 'required|array',
            'jumlah_742.*'    => 'required|numeric|min:1',
            'harga_jual_742'  => 'required|array',
            'harga_jual_742.*'=> 'required|numeric|min:0',
            'gambar_742'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'gambar_742.image' => 'File yang diunggah harus berupa gambar.',
            'gambar_742.mimes' => 'Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.',
            'gambar_742.max'   => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);
    }

    private function processItemsAndStock($barangIds, $jumlahs, $hargas, $action = 'decrement')
    {
        $items = [];
        $total = 0;
        foreach ($barangIds as $i => $id) {
            $qty = (int) $jumlahs[$i];
            $price = (int) $hargas[$i];
            $subtotal = $qty * $price;
            $total += $subtotal;

            $items[] = ['barang_id' => $id, 'jumlah' => $qty, 'harga' => $price, 'subtotal' => $subtotal];

            $barang = StokBarang_719::find($id);
            if ($barang) $barang->$action('719_stok_tercatat', $qty);
        }
        return [$items, $total];
    }

    private function restoreStock(array $items)
    {
        foreach ($items as $item) {
            $barang = StokBarang_719::find($item['barang_id']);
            if ($barang) $barang->increment('719_stok_tercatat', $item['jumlah']);
        }
    }

    private function handleUpload(Request $request, $oldFile = null)
    {
        if ($request->hasFile('gambar_742')) {
            if ($oldFile) $this->deleteFile($oldFile);

            $file = $request->file('gambar_742');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $folder = $this->imageFolder(742, 'catatan_keluar_742');
            $file->storeAs($folder, $fileName, 'public');

            return $folder . '/' . $fileName;
        }
        return $oldFile;
    }

    private function deleteFile($value)
    {
        if (str_contains($value, '/')) {
            if (Storage::disk('public')->exists($value)) {
                Storage::disk('public')->delete($value);
            }
            return;
        }

        $path = public_path('uploads/catatan_keluar/' . $value);
        if (File::exists($path)) File::delete($path);
    }
}
