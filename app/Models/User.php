<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    public const ROLE_MAHASISWA = 'mahasiswa';
    public const ROLE_ALUMNI = 'alumni';

    protected $fillable = ['name', 'email', 'password', 'role', 'profile_photo'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ---------- Peran ----------

    public function isMahasiswa(): bool
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    public function isAlumni(): bool
    {
        return $this->role === self::ROLE_ALUMNI;
    }

    // ---------- Profil ----------

    public function mahasiswaProfile(): HasOne
    {
        return $this->hasOne(MahasiswaProfile::class);
    }

    public function alumniProfile(): HasOne
    {
        return $this->hasOne(AlumniProfile::class);
    }

    public function talentProfile(): HasOne
    {
        return $this->hasOne(TalentProfile::class);
    }

    public function mentorProfile(): HasOne
    {
        return $this->hasOne(MentorProfile::class, 'alumni_id');
    }

    // ---------- Job Referral ----------

    public function jobReferrals(): HasMany
    {
        return $this->hasMany(JobReferral::class, 'alumni_id');
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'mahasiswa_id');
    }

    // ---------- Koneksi & Mentoring ----------

    public function sentConnections(): HasMany
    {
        return $this->hasMany(Connection::class, 'mahasiswa_id');
    }

    public function receivedConnections(): HasMany
    {
        return $this->hasMany(Connection::class, 'alumni_id');
    }

    public function mentoringRequests(): HasMany
    {
        return $this->hasMany(MentoringRequest::class, 'mahasiswa_id');
    }

    // ---------- Notifikasi ----------
    // Sengaja tidak memakai trait Notifiable bawaan Laravel,
    // karena tabel "notifications" milik tim punya struktur sendiri.

    public function inbox(): HasMany
    {
        return $this->hasMany(UserNotification::class)->latest();
    }

    // ---------- Tampilan ----------

    public function initials(): string
    {
        return collect(explode(' ', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }

    public function headline(): ?string
    {
        if ($this->isAlumni()) {
            $p = $this->alumniProfile;

            return $p ? trim(collect([$p->job_title, $p->company])->filter()->implode(' at '), ' ') ?: null : null;
        }

        return $this->talentProfile?->headline ?? $this->mahasiswaProfile?->study_program;
    }

    public function photoUrl(): ?string
    {
        return $this->profile_photo ? route('photos.show', $this) : null;
    }
}
