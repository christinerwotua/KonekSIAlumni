<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DirectoryConnectionsSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'mahasiswa1@example.test')
            ->where('role', 'mahasiswa')->firstOrFail();
        $alumniOne = User::where('email', 'alumni1@example.test')
            ->where('role', 'alumni')->firstOrFail();
        $alumniTwo = User::where('email', 'alumni2@example.test')
            ->where('role', 'alumni')->firstOrFail();

        $profiles = [
            [$alumniOne, [
                'graduation_year' => 2020,
                'company' => 'PT Contoh Data Nusantara',
                'job_title' => 'Data Analyst',
                'bio' => 'Alumni demo yang bekerja di bidang analisis data.',
            ]],
            [$alumniTwo, [
                'graduation_year' => 2022,
                'company' => 'PT Rekacipta Digital Demo',
                'job_title' => 'Product Designer',
                'bio' => 'Alumni demo yang bekerja di bidang desain produk digital.',
            ]],
        ];

        $timestamp = now();

        DB::transaction(function () use ($profiles, $student, $alumniOne, $timestamp) {
            foreach ($profiles as [$alumnus, $profile]) {
                DB::table('alumni_profiles')->updateOrInsert(
                    ['user_id' => $alumnus->id],
                    fn (bool $exists) => $profile + ['updated_at' => $timestamp]
                        + ($exists ? [] : ['created_at' => $timestamp]),
                );
            }

            DB::table('connections')->updateOrInsert(
                ['mahasiswa_id' => $student->id, 'alumni_id' => $alumniOne->id],
                fn (bool $exists) => $exists ? [] : [
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ],
            );
        });
    }
}
