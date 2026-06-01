<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@myuos.com',
            'password' => Hash::make('password'),
            'role'     => 'superadmin',
        ]);

        User::create([
            'name'     => 'Budi Manager',
            'email'    => 'manager@myuos.com',
            'password' => Hash::make('password'),
            'role'     => 'manager',
        ]);

        User::create([
            'name'     => 'Sari Kasir',
            'email'    => 'kasir@myuos.com',
            'password' => Hash::make('password'),
            'role'     => 'kasir',
        ]);
    }
}