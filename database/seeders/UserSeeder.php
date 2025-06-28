<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['user', 'admin', 'karyawan', 'ceo'];

        foreach ($roles as $role) {
            for ($i = 1; $i <= 1; $i++) {
                User::create([
                    'name' => strtoupper($role), // KAPITAL
                    'email' => $role . $i . '@example.com',
                    'password' => Hash::make('password123'),
                    'role' => $role,
                ]);
            }
        }

        // Opsional: Super Admin
        User::create([
            'name' => 'SUPER ADMIN',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}
