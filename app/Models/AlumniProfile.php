<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniProfile extends Model
{
    protected $fillable = ['user_id', 'graduation_year', 'company', 'job_title', 'bio'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
