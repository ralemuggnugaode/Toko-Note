<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'                  => 'Edo Agung Gumelar',
                'username'              => 'admin719',
                'password'              => Hash::make('123'),
                'role'                  => 'admin',
                'identification_number' => '719',
            ],
            [
                'name'                  => 'Bernadus Damiano M.P',
                'username'              => 'admin729',
                'password'              => Hash::make('456'),
                'role'                  => 'admin',
                'identification_number' => '729',
            ],
            [
                'name'                  => 'Fradesta Leksa Saputra',
                'username'              => 'admin742',
                'password'              => Hash::make('789'),
                'role'                  => 'admin',
                'identification_number' => '742',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
