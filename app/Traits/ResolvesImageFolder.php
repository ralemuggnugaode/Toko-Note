<?php

namespace App\Traits;

trait ResolvesImageFolder
{
    /**
     * Tentukan folder tujuan penyimpanan gambar berdasarkan genap/ganjilnya
     * NOMOR MODUL/HALAMAN itu sendiri (719, 729, atau 742) — bukan berdasarkan
     * siapa yang sedang login, karena user manapun bisa mengakses halaman manapun.
     *
     * Contoh hasil: imageFolder(729, 'catatan_masuk_729') -> "ganjil/catatan_masuk_729"
     *               imageFolder(742, 'catatan_keluar_742') -> "genap/catatan_keluar_742"
     *
     * @param  int|string  $moduleNumber  Nomor modul, mis. 719, 729, atau 742.
     * @param  string      $moduleFolder  Nama subfolder modul, mis. 'barang_719'.
     */
    protected function imageFolder($moduleNumber, string $moduleFolder): string
    {
        $parity = ((int) $moduleNumber % 2 === 0) ? 'genap' : 'ganjil';

        return $parity . '/' . $moduleFolder;
    }
}
