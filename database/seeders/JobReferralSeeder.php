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

        DB::table('job_referrals')->updateOrInsert(
            ['alumni_id' => $alumnus->id, 'title' => $title],
            [
                'company'              => 'PT Contoh Data Nusantara',
                'location'             => 'Jakarta (Hybrid)',
                'description'          => 'Magang 3 bulan: membersihkan data, membuat dashboard, dan laporan mingguan.',
                'requirements'         => 'Mahasiswa aktif semester 5+, menguasai SQL dan Excel.',
                'application_deadline' => '2026-12-31 23:59:00',
                'status'               => 'open',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
        );

        $jobId = DB::table('job_referrals')
            ->where('alumni_id', $alumnus->id)
            ->where('title', $title)
            ->value('id');

        // CV mahasiswa1 dari TalentProfileSeeder (harus dijalankan lebih dulu)
        $cvId = DB::table('talent_documents')
            ->join('talent_profiles', 'talent_profiles.id', '=', 'talent_documents.talent_profile_id')
            ->where('talent_profiles.user_id', $student->id)
            ->where('talent_documents.type', 'cv')
            ->value('talent_documents.id');

        DB::table('job_applications')->updateOrInsert(
            ['job_referral_id' => $jobId, 'mahasiswa_id' => $student->id],
            [
                'cv_id'      => $cvId,
                'message'    => 'Saya tertarik dengan posisi ini dan sudah terbiasa memakai SQL.',
                'status'     => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
