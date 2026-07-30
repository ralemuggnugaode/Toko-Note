<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User7;
use Illuminate\Support\Facades\Hash;

class User7Seeder extends Seeder
{
    public function run(): void
    {
        User7::create([
            'username' => '719',
            'password' => Hash::make('719'),
            'nama'     => 'Stok Barang',
        ]);

        User7::create([
            'username' => '729',
            'password' => Hash::make('729'),
            'nama'     => 'Catatan Masuk',
        ]);

        User7::create([
            'username' => '742',
            'password' => Hash::make('742'),
            'nama'     => 'Catatan Keluar',
        ]);
    }
}
