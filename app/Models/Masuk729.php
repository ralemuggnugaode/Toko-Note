<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masuk729 extends Model
{
    use HasFactory;

    protected $table = 'masuk_729';

    protected $fillable = [
    'tanggal', 'pihak', 'nomor', 'items', 'total', 'keterangan', 'gambar', 'gambar_original'
];


    protected $casts = [
        'items' => 'array',
        'tanggal' => 'date',
    ];
}
