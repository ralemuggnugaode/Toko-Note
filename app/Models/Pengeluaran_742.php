<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran_742 extends Model
{
    use HasFactory;

    protected $table = 'catatan_keluar_742';

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

    public function getGambarUrlAttribute(): ?string
    {
        if (empty($this->gambar_742)) {
            return null;
        }

        if (str_contains($this->gambar_742, '/')) {
            return asset('storage/' . $this->gambar_742);
        }

        return asset('uploads/catatan_keluar/' . $this->gambar_742);
    }
}
