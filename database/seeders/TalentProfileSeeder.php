<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalentProfileSeeder extends Seeder
{
    public function run(): void
    {
        $student1 = User::where('email', 'mahasiswa1@example.test')->firstOrFail();
        $student2 = User::where('email', 'mahasiswa2@example.test')->firstOrFail();

        DB::table('talent_profiles')->updateOrInsert(
            ['user_id' => $student1->id],
            [
                'headline' => 'Mahasiswa Sistem Informasi',
                'bio' => 'Tertarik pada pengembangan sistem informasi dan analisis data.',
                'skills' => 'SQL, Excel, UI Design',
                'experience' => 'Asisten praktikum Basis Data (2025).',
                'is_visible' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('talent_profiles')->updateOrInsert(
            ['user_id' => $student2->id],
            [
                'headline' => 'Mahasiswa Sistem Informasi',
                'bio' => 'Memiliki minat pada pengembangan web dan manajemen proyek.',
                'skills' => 'Laravel, PHP, Project Management',
                'experience' => 'Panitia divisi IT acara kampus (2025).',
                'is_visible' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // CV demo milik mahasiswa1, dipakai di lamaran job dan pengajuan mentoring
        $talentId = DB::table('talent_profiles')->where('user_id', $student1->id)->value('id');

        DB::table('talent_documents')->updateOrInsert(
            ['talent_profile_id' => $talentId, 'type' => 'cv'],
            [
                'file_name' => 'CV_Mahasiswa_Demo_1.pdf',
                'file_path' => 'talent-documents/cv-mahasiswa-demo-1.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
