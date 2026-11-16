<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MentoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswa = User::where('role', 'mahasiswa')->first();
        $alumni = User::where('role', 'alumni')->first();

        $mahasiswaId = $mahasiswa?->id ?? 1;
        $alumniId = $alumni?->id ?? 2;

        // Data 1: Persiapan magang (Status pending)
        DB::table('mentoring_requests')->updateOrInsert(
            [
                'mahasiswa_id' => $mahasiswaId,
                'alumni_id' => $alumniId,
                'topic' => 'Persiapan magang',
            ],
            [
                'message' => 'Halo Kak, saya ingin minta bimbingan terkait persiapan magang.',
                'status' => 'pending',
                'response_message' => null,
                'responded_at' => null,
            ]
        );

        // Data 2: Review CV (Status accepted)
        DB::table('mentoring_requests')->updateOrInsert(
            [
                'mahasiswa_id' => $mahasiswaId,
                'alumni_id' => $alumniId,
                'topic' => 'Review CV',
            ],
            [
                'message' => 'Halo Kak, boleh minta tolong review CV saya?',
                'status' => 'accepted',
                'response_message' => 'Terima kasih kak, saya sudah review CV saya.',
                'responded_at' => now(),
            ]
        );
    }
}
