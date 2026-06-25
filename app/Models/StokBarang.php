<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    use HasFactory;

    protected $table = 'stok_barang_719';

    protected $fillable = [
        'id',
        '719_kode',
        '719_nama',
        '719_kategori',
        '719_harga_beli',
        '719_harga_jual',
        '719_stok_min',
        '719_stok_tercatat',
    ];
}
