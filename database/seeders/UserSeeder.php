<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Seeder;
use App\Models\User;
=======
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
>>>>>>> 43773f0907c635af242290f113bc7a48de03194f
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        User::create([
            'name'     => 'Desta',
            'email'    => 'desta@gmail.com',
            'password' => Hash::make('orangbaik'), // Password untuk login nanti
        ]);
=======
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
                'name'                  => 'Admin 742',
                'username'              => 'Fradesta Leksa S',
                'password'              => Hash::make('789'),
                'role'                  => 'admin',
                'identification_number' => '742',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
>>>>>>> 43773f0907c635af242290f113bc7a48de03194f
    }
}
