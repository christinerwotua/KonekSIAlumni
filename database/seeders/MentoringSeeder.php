<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MentoringSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::where('email', 'mahasiswa1@example.test')->firstOrFail();
        $alumniOne = User::where('email', 'alumni1@example.test')->firstOrFail();
        $alumniTwo = User::where('email', 'alumni2@example.test')->firstOrFail();

        // Profil mentor untuk kedua alumni
        $mentors = [
            [$alumniOne, 'Analisis data, SQL, persiapan magang', 'Siap berbagi pengalaman magang dan karier di bidang data.'],
            [$alumniTwo, 'Desain produk digital, UI/UX', 'Membantu review portofolio desain.'],
        ];

        foreach ($mentors as [$alumnus, $expertise, $description]) {
            DB::table('mentor_profiles')->updateOrInsert(
                ['alumni_id' => $alumnus->id],
                [
                    'expertise' => $expertise,
                    'description' => $description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            );
        }

        $mentorId = DB::table('mentor_profiles')->where('alumni_id', $alumniOne->id)->value('id');

        // CV mahasiswa1 dari TalentProfileSeeder (harus dijalankan lebih dulu)
        $cvId = DB::table('talent_documents')
            ->join('talent_profiles', 'talent_profiles.id', '=', 'talent_documents.talent_profile_id')
            ->where('talent_profiles.user_id', $student->id)
            ->where('talent_documents.type', 'cv')
            ->value('talent_documents.id');

        DB::table('mentoring_requests')->updateOrInsert(
            ['mentor_id' => $mentorId, 'mahasiswa_id' => $student->id, 'topic' => 'Persiapan magang'],
            [
                'cv_id' => null,
                'goal' => 'Ingin tahu langkah persiapan magang di bidang data.',
                'proposed_schedule' => '2026-11-25 19:00:00',
                'status' => 'pending',
                'response_message' => null,
                'responded_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('mentoring_requests')->updateOrInsert(
            ['mentor_id' => $mentorId, 'mahasiswa_id' => $student->id, 'topic' => 'Review CV'],
            [
                'cv_id' => $cvId,
                'goal' => 'Minta masukan untuk CV sebelum melamar magang.',
                'proposed_schedule' => '2026-11-27 19:00:00',
                'status' => 'accepted',
                'response_message' => 'Boleh, siapkan CV yang akan dibahas.',
                'responded_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
