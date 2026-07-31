<?php

namespace App\Http\Controllers;

use App\Models\Masuk729;
use App\Models\StokBarang_719;
use App\Traits\ResolvesImageFolder;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CatatanMasukController extends Controller
{
    use ResolvesImageFolder, LogActivity;

    public function index()
    {
        $daftarBarang = StokBarang_719::all();
        $masukTerakhir = Masuk729::latest()->take(5)->get();

        return view('pages.catatan_masuk_729', compact('daftarBarang', 'masukTerakhir'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            '729_tanggal'    => 'required|date',
            '729_pihak'      => 'required|string|max:255',
            '729_nomor'      => 'nullable|string|max:50',
            '729_barang_id.*' => 'required|string',
            '729_jumlah.*'    => 'required|integer|min:1',
            '729_harga.*'     => 'required|numeric|min:0',
            '729_total'       => 'required|numeric|min:0',
            '729_keterangan'  => 'nullable|string',
            '729_gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $items = [];
        $barangIds = $request->input('729_barang_id', []);
        $jumlahs   = $request->input('729_jumlah', []);
        $hargas    = $request->input('729_harga', []);
        $namaLain  = $request->input('729_nama_barang_lain', []);
        $namaBarangHidden = $request->input('729_nama_barang', []);

        foreach ($barangIds as $i => $barangId) {
            if (empty($barangId) || empty($jumlahs[$i])) {
                continue;
            }

            $item = [
                'barang_id' => $barangId,
                'jumlah'    => (int) $jumlahs[$i],
                'harga'     => (int) $hargas[$i],
                'subtotal'  => (int) $jumlahs[$i] * (int) $hargas[$i],
            ];

            if ($barangId === 'LAINNYA' && isset($namaLain[$i])) {
                $item['nama_barang_lain'] = trim($namaLain[$i]);
            } elseif (!empty($namaBarangHidden[$i])) {
                $item['nama_barang'] = trim($namaBarangHidden[$i]);
            }

            $items[] = $item;
        }

        $gambarPath = null;
        $gambarOriginal = null;
        if ($request->hasFile('729_gambar') && $request->file('729_gambar')->isValid()) {
            $file = $request->file('729_gambar');
            $gambarOriginal = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newName = date('Ymd_His') . '_' . Str::random(10) . '.' . $extension;

            $folder = $this->imageFolder(729, 'catatan_masuk_729');
            $file->storeAs($folder, $newName, 'public');
            $gambarPath = $folder . '/' . $newName;
        }

        $nomor = $request->input('729_nomor');
        if (empty($nomor)) {
            $nomor = 'IN-' . date('Ymd') . '-' . rand(100, 999);
        }

        DB::transaction(function () use ($request, $items, $nomor, $gambarPath, $gambarOriginal) {
            $masuk = Masuk729::create([
                'tanggal'    => $request->input('729_tanggal'),
                'pihak'      => $request->input('729_pihak'),
                'nomor'      => $nomor,
                'items'      => $items,
                'total'      => (int) $request->input('729_total'),
                'keterangan' => $request->input('729_keterangan', ''),
                'gambar'     => $gambarPath,
                'gambar_original' => $gambarOriginal,
            ]);

            // Update stok barang berdasarkan barang yang masuk
            $this->applyStokMasuk($items, $gambarPath);
        });

        return redirect()->route('page.catatan-masuk-729.index')->with('success', 'Catatan masuk berhasil disimpan dan stok barang telah diperbarui!');
    }

    private function cariBarangSamaNama(string $nama)
    {
        return StokBarang_719::whereRaw('LOWER(TRIM(719_nama)) = ?', [strtolower(trim($nama))])->first();
    }

    /**
     * penjelasan untuk presentasi salin (copy fisik) foto nota catatan masuk ke folder foto produk Stok Barang,
     * supaya barang baru dari "Lainnya" langsung punya foto, dan file-nya independen
     * dari foto nota aslinya (aman kalau nota-nya nanti diganti/dihapus).
     */
    private function salinFotoUntukBarangBaru(?string $gambarNota): string
    {
        if (empty($gambarNota) || !Storage::disk('public')->exists($gambarNota)) {
            return '';
        }

        $extension = pathinfo($gambarNota, PATHINFO_EXTENSION);
        $namaBaru = date('Ymd_His') . '_' . Str::random(10) . ($extension ? '.' . $extension : '');
        $folderTujuan = $this->imageFolder(719, 'barang_719');
        $pathTujuan = $folderTujuan . '/' . $namaBaru;

        Storage::disk('public')->copy($gambarNota, $pathTujuan);

        return $pathTujuan;
    }

    /**
     * penjelasan presentasi tambahkan stok ke StokBarang_719 sesuai daftar item catatan masuk.
     * Dipakai saat store() dan saat update() menerapkan item baru.
     *
     * @param  string|null  $gambarNota  alur gambar path , Path foto yang di-upload di form catatan masuk (disk 'public').
     *                                   Untuk barang BARU ('LAINNYA'), foto ini disalin (copy fisik,
     *                                   bukan sekadar link) supaya jadi foto produk di Stok Barang,
     *                                   tanpa ikut terhapus kalau foto nota di catatan masuknya diganti/dihapus.
     */
    private function applyStokMasuk(array $items, ?string $gambarNota = null): void
    {
        foreach ($items as $item) {
            if ($item['barang_id'] === 'LAINNYA') {
                $namaBaru = trim($item['nama_barang_lain'] ?? 'Barang Baru');
                $barangSama = $this->cariBarangSamaNama($namaBaru);

                if ($barangSama) {
                    $barangSama->increment('719_stok_tercatat', $item['jumlah']);
                    $barangSama->update(['719_harga_beli' => $item['harga']]);
                    continue;
                }

                do {
                    $kodeBaru = 'BRG' . rand(100, 999);
                } while (StokBarang_719::where('719_kode', $kodeBaru)->exists());

                StokBarang_719::create([
                    '719_kode'          => $kodeBaru,
                    '719_gambar'        => $this->salinFotoUntukBarangBaru($gambarNota),
                    '719_nama'          => $namaBaru,
                    '719_kategori'      => 'Lain-lain',
                    '719_harga_beli'    => $item['harga'],
                    '719_harga_jual'    => $item['harga'],
                    '719_stok_min'      => 0,
                    '719_stok_tercatat' => $item['jumlah'],
                ]);
            } else {
                $barang = StokBarang_719::where('719_kode', $item['barang_id'])->first();

                if (!$barang && !empty($item['nama_barang'])) {
                    $barang = $this->cariBarangSamaNama($item['nama_barang']);
                }

                if ($barang) {
                    $barang->increment('719_stok_tercatat', $item['jumlah']);
                    $barang->update(['719_harga_beli' => $item['harga']]);
                }
            }
        }
    }

    private function reverseStokMasuk(array $items): void
    {
        foreach ($items as $item) {
            $barang = null;

            if ($item['barang_id'] === 'LAINNYA') {
                $nama = $item['nama_barang_lain'] ?? null;
                if ($nama) {
                    $barang = $this->cariBarangSamaNama($nama);
                }
            } else {
                $barang = StokBarang_719::where('719_kode', $item['barang_id'])->first();

                if (!$barang && !empty($item['nama_barang'])) {
                    $barang = $this->cariBarangSamaNama($item['nama_barang']);
                }
            }

            if ($barang) {
                $stokBaru = max(0, $barang->{'719_stok_tercatat'} - $item['jumlah']);
                $barang->update(['719_stok_tercatat' => $stokBaru]);
            }
        }
    }

    public function edit($id)
    {
        $masuk = Masuk729::findOrFail($id);
        $daftarBarang = StokBarang_719::all();

        return view('pages.catatan_masuk_729_edit', compact('masuk', 'daftarBarang'));
    }

    public function update(Request $request, $id)
    {
        $masuk = Masuk729::findOrFail($id);

        $validator = Validator::make($request->all(), [
            '729_tanggal'    => 'required|date',
            '729_pihak'      => 'required|string|max:255',
            '729_nomor'      => 'nullable|string|max:50',
            '729_barang_id.*' => 'required|string',
            '729_jumlah.*'    => 'required|integer|min:1',
            '729_harga.*'     => 'required|numeric|min:0',
            '729_total'       => 'required|numeric|min:0',
            '729_keterangan'  => 'nullable|string',
            '729_gambar'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $items = [];
        $barangIds = $request->input('729_barang_id', []);
        $jumlahs   = $request->input('729_jumlah', []);
        $hargas    = $request->input('729_harga', []);
        $namaLain  = $request->input('729_nama_barang_lain', []);
        $namaBarangHidden = $request->input('729_nama_barang', []);

        foreach ($barangIds as $i => $barangId) {
            if (empty($barangId) || empty($jumlahs[$i])) {
                continue;
            }

            $item = [
                'barang_id' => $barangId,
                'jumlah'    => (int) $jumlahs[$i],
                'harga'     => (int) $hargas[$i],
                'subtotal'  => (int) $jumlahs[$i] * (int) $hargas[$i],
            ];

            if ($barangId === 'LAINNYA' && isset($namaLain[$i])) {
                $item['nama_barang_lain'] = trim($namaLain[$i]);
            } elseif (!empty($namaBarangHidden[$i])) {
                $item['nama_barang'] = trim($namaBarangHidden[$i]);
            }

            $items[] = $item;
        }

        $gambarPath = $masuk->gambar;
        $gambarOriginal = $masuk->gambar_original;
        if ($request->hasFile('729_gambar') && $request->file('729_gambar')->isValid()) {
            if ($masuk->gambar && Storage::disk('public')->exists($masuk->gambar)) {
                Storage::disk('public')->delete($masuk->gambar);
            }

            $file = $request->file('729_gambar');
            $gambarOriginal = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $newName = date('Ymd_His') . '_' . Str::random(10) . '.' . $extension;

            $folder = $this->imageFolder(729, 'catatan_masuk_729');
            $file->storeAs($folder, $newName, 'public');
            $gambarPath = $folder . '/' . $newName;
        }

        $nomor = $request->input('729_nomor');
        if (empty($nomor)) {
            $nomor = $masuk->nomor;
        }

        $itemsLama = $masuk->items ?? [];

        DB::transaction(function () use ($masuk, $request, $items, $itemsLama, $nomor, $gambarPath, $gambarOriginal) {
            $this->reverseStokMasuk($itemsLama);
            $this->applyStokMasuk($items, $gambarPath);

            $masuk->update([
                'tanggal'    => $request->input('729_tanggal'),
                'pihak'      => $request->input('729_pihak'),
                'nomor'      => $nomor,
                'items'      => $items,
                'total'      => (int) $request->input('729_total'),
                'keterangan' => $request->input('729_keterangan', ''),
                'gambar'     => $gambarPath,
                'gambar_original' => $gambarOriginal,
            ]);

            $this->logActivity($masuk, 'update', $request->all());
        });

        return redirect()->route('page.catatan-masuk-729.index')->with('success', 'Catatan masuk berhasil diperbarui dan stok barang telah disesuaikan!');
    }

    public function destroy($id)
    {
        $masuk = Masuk729::findOrFail($id);

        DB::transaction(function () use ($masuk) {
            $this->reverseStokMasuk($masuk->items ?? []);

            if ($masuk->gambar && Storage::disk('public')->exists($masuk->gambar)) {
                Storage::disk('public')->delete($masuk->gambar);
            }

            $masuk->delete();
            $this->logActivity($masuk, 'delete');
        });

        return redirect()->route('page.catatan-masuk-729.index')->with('success', 'Catatan masuk berhasil dihapus dan stok barang telah disesuaikan!');
    }
}
