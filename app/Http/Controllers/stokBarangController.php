<?php

namespace App\Http\Controllers;

use App\Models\StokBarang_719;
use App\Traits\ResolvesImageFolder;
use App\Traits\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class stokBarangController extends Controller
{
    use ResolvesImageFolder, LogActivity;

    public function index()
    {
        $barangs = StokBarang_719::all();
        return view('pages.stokBarang_719', compact('barangs'))->with('title', 'Stok Barang');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            '719_gambar'         => 'max:5120|required|file|image',
            '719_kode'           => 'nullable|string|max:10',
            '719_nama'           => 'required|string|max:255',
            '719_kategori'       => 'required|string|max:255',
            '719_harga_beli'     => 'required|numeric',
            '719_harga_jual'     => 'required|numeric',
            '719_stok_min'       => 'required|integer',
            '719_stok_tercatat'  => 'required|integer',
        ]);

        $validated['719_gambar'] = $request->file('719_gambar')->store($this->imageFolder(719, 'barang_719'));

        $kode = $validated['719_kode'] ?? null;

        if ($kode) {
            while (StokBarang_719::where('719_kode', $kode)->exists()) {
                $kode = 'BRG' . rand(100, 999);
            }
        } else {
            do {
                $kode = 'BRG' . rand(100, 999);
            } while (StokBarang_719::where('719_kode', $kode)->exists());
        }

        $barang = StokBarang_719::create([
            '719_kode'          => $kode,
            '719_gambar'        => $validated['719_gambar'],
            '719_nama'          => $validated['719_nama'],
            '719_kategori'      => $validated['719_kategori'],
            '719_harga_beli'    => $validated['719_harga_beli'],
            '719_harga_jual'    => $validated['719_harga_jual'],
            '719_stok_min'      => $validated['719_stok_min'],
            '719_stok_tercatat' => $validated['719_stok_tercatat'],
        ]);

        $this->logActivity($barang, 'create', $request->all());

        return redirect()->route('page.stok-barang-719.index')
            ->with('success', 'Barang berhasil disimpan.');
    }

    public function show(StokBarang_719 $stokBarang)
    {
        //
    }

    public function edit(StokBarang_719 $stokBarang)
    {
        return view('pages.editStokBarang', compact('stokBarang'))->with('title', 'Edit Barang');
    }

    public function update(Request $request, StokBarang_719 $stokBarang)
    {
        $validated = $request->validate([
            '719_gambar'         => 'max:5120|nullable|file|image',
            '719_kode'           => 'nullable|string|max:10',
            '719_nama'           => 'required|string|max:255',
            '719_kategori'       => 'required|string|max:255',
            '719_harga_beli'     => 'required|numeric',
            '719_harga_jual'     => 'required|numeric',
            '719_stok_min'       => 'required|integer',
            '719_stok_tercatat'  => 'required|integer',
        ]);

        if ($request->hasFile('719_gambar')) {
            if ($stokBarang->{'719_gambar'} && Storage::exists($stokBarang->{'719_gambar'})) {
                Storage::delete($stokBarang->{'719_gambar'});
            }
            $path = $request->file('719_gambar')->store($this->imageFolder(719, 'barang_719'));
            $validated['719_gambar'] = $path;
        } else {
            $validated['719_gambar'] = $stokBarang->{'719_gambar'};
        }

        $kode = $validated['719_kode'] ?? null;

        if (!$kode) {
            $kode = $stokBarang->{'719_kode'};
        } else {
            if ($kode !== $stokBarang->{'719_kode'}) {
                $existing = StokBarang_719::where('719_kode', $kode)
                    ->where('id', '!=', $stokBarang->id)
                    ->exists();
                if ($existing) {
                    return back()->withErrors(['719_kode' => 'Kode barang sudah digunakan'])->withInput();
                }
            }
        }

        $stokBarang->update([
            '719_kode' => $kode,
            '719_gambar' => $validated['719_gambar'],
            '719_nama' => $validated['719_nama'],
            '719_kategori' => $validated['719_kategori'],
            '719_harga_beli' => $validated['719_harga_beli'],
            '719_harga_jual' => $validated['719_harga_jual'],
            '719_stok_min' => $validated['719_stok_min'],
            '719_stok_tercatat' => $validated['719_stok_tercatat'],
        ]);

        $this->logActivity($stokBarang, 'update', $request->all());

        return redirect()->route('page.stok-barang-719.index')->with('success', 'Data barang berhasil diperbarui');
    }

    public function destroy(StokBarang_719 $stokBarang)
    {
        $pathGambar = $stokBarang->{'719_gambar'};
        if ($pathGambar && Storage::exists($pathGambar)) {
            Storage::delete($pathGambar);
        }
        $stokBarang->delete();

        $this->logActivity($stokBarang, 'delete');

        return redirect()
            ->route('page.stok-barang-719.index')
            ->with('success', 'Barang berhasil dihapus');
    }
}
