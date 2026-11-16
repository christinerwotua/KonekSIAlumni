<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        $studentProfiles = [
            'mahasiswa1@example.test' => ['nim' => '5026231001', 'study_program' => 'Sistem Informasi'],
            'mahasiswa2@example.test' => ['nim' => '5026231002', 'study_program' => 'Sistem Informasi'],
        ];

        foreach ($studentProfiles as $email => $profile) {
            $student = User::where('email', $email)->firstOrFail();

            DB::table('mahasiswa_profiles')->updateOrInsert(
                ['user_id' => $student->id],
                $profile + ['created_at' => now(), 'updated_at' => now()],
            );
        }
    }
}
