<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran_742 extends Model
{
    use HasFactory;

    protected $table = 'catatan_keluar_742';

    // Sudah ditambahkan 'gambar_742' agar diizinkan masuk database
    protected $fillable = [
        'barangid_742',
        'tanggal_742',
        'pihak_742',
        'nomor_742',
        'keterangan_742',
        'gambar_742',
        'items_742',
        'total_742',
        'created_at',
        'updated_at',
    ];
}
