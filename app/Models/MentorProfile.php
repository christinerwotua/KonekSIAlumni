<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MentorProfile extends Model
{
    protected $fillable = ['alumni_id', 'expertise', 'description'];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(MentoringRequest::class, 'mentor_id');
    }

    /** Keahlian disimpan sebagai teks dipisah koma, ditampilkan sebagai chip. */
    public function expertiseList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->expertise))));
    }
}
