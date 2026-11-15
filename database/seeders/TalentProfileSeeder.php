<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
                'is_visible' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
