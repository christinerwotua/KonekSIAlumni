<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorProfile extends Model
{
    protected $fillable = [
        'alumni_id',
        'expertise',
        'description',
    ];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }

    public function expertiseList(): array
    {
        return collect(
            preg_split('/[,;\n]+/', $this->expertise ?? '')
        )
            ->map(fn ($skill) => trim($skill))
            ->filter()
            ->values()
            ->all();
    }
}
