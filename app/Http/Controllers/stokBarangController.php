<?php

namespace App\Http\Controllers;

use App\Models\StokBarang_719;
use Illuminate\Http\Request;

class stokBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = StokBarang_719::all();
        return view('pages.stokBarang_719',compact('barangs'))->with('title', 'Stok Barang');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            '719_kode'           => 'nullable|string|max:10',
            '719_nama'           => 'required|string|max:255',
            '719_kategori'       => 'required|string|max:255',
            '719_harga_beli'     => 'required|numeric',
            '719_harga_jual'     => 'required|numeric',
            '719_stok_min'       => 'required|integer',
            '719_stok_tercatat'  => 'required|integer',
        ]);

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
            '719_nama'          => $validated['719_nama'],
            '719_kategori'      => $validated['719_kategori'],
            '719_harga_beli'    => $validated['719_harga_beli'],
            '719_harga_jual'    => $validated['719_harga_jual'],
            '719_stok_min'      => $validated['719_stok_min'],
            '719_stok_tercatat' => $validated['719_stok_tercatat'],
        ]);

        return redirect()->route('stok-barang-719.index')
                         ->with('success', 'Barang berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StokBarang_719 $stokBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StokBarang_719 $stokBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StokBarang_719 $stokBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StokBarang_719 $stokBarang, $id)
    {
        $getStokBarang = $stokBarang->findOrFail($id);
        $getStokBarang->delete();
        return redirect()
        ->route('stok-barang-719.index')
        ->with('success', 'Barang berhasil dihapus');
    }
}
