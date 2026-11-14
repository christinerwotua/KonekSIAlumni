<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Mahasiswa Demo 1', 'email' => 'mahasiswa1@example.test', 'role' => 'mahasiswa'],
            ['name' => 'Mahasiswa Demo 2', 'email' => 'mahasiswa2@example.test', 'role' => 'mahasiswa'],
            ['name' => 'Alumni Demo 1', 'email' => 'alumni1@example.test', 'role' => 'alumni'],
            ['name' => 'Alumni Demo 2', 'email' => 'alumni2@example.test', 'role' => 'alumni'],
        ];

        foreach ($accounts as $account) {
            User::firstOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'role' => $account['role'],
                    'password' => Hash::make('DemoKonekSI123!'),
                ],
            );
        }
    }
}
