<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MahasiswaProfile extends Model
{
    protected $fillable = ['user_id', 'nim', 'study_program'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
