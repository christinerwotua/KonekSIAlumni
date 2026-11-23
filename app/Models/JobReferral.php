<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobReferral extends Model
{
    protected $fillable = [
        'alumni_id', 'title', 'company', 'location', 'description',
        'requirements', 'application_deadline', 'status',
    ];

    protected function casts(): array
    {
        return ['application_deadline' => 'datetime'];
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open'
            && (! $this->application_deadline || $this->application_deadline->endOfDay()->isFuture());
    }

    public function isNew(): bool
    {
        return $this->created_at && $this->created_at->gt(now()->subDays(3));
    }

    /** Persyaratan ditulis satu per baris, ditampilkan sebagai daftar. */
    public function requirementList(): array
    {
        return array_values(array_filter(array_map(
            fn ($line) => trim(ltrim(trim($line), '-•*')),
            preg_split('/\r\n|\r|\n/', (string) $this->requirements)
        )));
    }
}
