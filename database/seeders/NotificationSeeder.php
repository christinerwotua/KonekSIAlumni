<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'mahasiswa1@example.test')->firstOrFail();
        $alumnus = User::where('email', 'alumni1@example.test')->firstOrFail();

        $notifications = [
            [$student, 'mentoring', 'Pengajuan mentoring diterima', 'Alumni Demo 1 menerima pengajuan mentoring "Review CV".', '/mentoring'],
            [$alumnus, 'job_referral', 'Ada pelamar baru', 'Mahasiswa Demo 1 melamar lowongan Data Analyst Intern — Demo.', '/job-referrals'],
        ];

        foreach ($notifications as [$user, $type, $title, $message, $link]) {
            DB::table('notifications')->updateOrInsert(
                ['user_id' => $user->id, 'title' => $title],
                [
                    'type' => $type,
                    'message' => $message,
                    'link' => $link,
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }
    }
}
