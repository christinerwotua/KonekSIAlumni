<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentProfile extends Model
{
    protected $fillable = ['user_id', 'headline', 'bio', 'skills', 'experience', 'is_visible'];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TalentDocument::class);
    }

    public function skillList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->skills))));
    }
}
