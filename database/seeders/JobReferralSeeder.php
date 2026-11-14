<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobReferralSeeder extends Seeder
{
    public function run(): void
    {
        $alumnus = User::where('email', 'alumni1@example.test')->firstOrFail();
        $student = User::where('email', 'mahasiswa1@example.test')->firstOrFail();

        $title = 'Data Analyst Intern — Demo';

        // 1 lowongan dari alumni1
        DB::table('job_referrals')->updateOrInsert(
            ['alumni_id' => $alumnus->id, 'title' => $title],
            [
                'company'     => 'PT Contoh Data Nusantara',
                'location'    => 'Jakarta (Hybrid)',
                'description' => 'Magang 3 bulan: membersihkan data, membuat dashboard, dan laporan mingguan.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        );

        $jobId = DB::table('job_referrals')
            ->where('alumni_id', $alumnus->id)
            ->where('title', $title)
            ->value('id');

        // 1 lamaran dari mahasiswa1 ke lowongan itu
        DB::table('job_applications')->updateOrInsert(
            ['job_referral_id' => $jobId, 'mahasiswa_id' => $student->id],
            ['created_at' => now(), 'updated_at' => now()],
        );
    }
}
