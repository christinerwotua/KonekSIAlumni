<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentoringRequest extends Model
{
    public const STATUS_LABELS = ['pending' => 'Menunggu', 'accepted' => 'Diterima', 'rejected' => 'Ditolak'];

    protected $fillable = [
        'mentor_id', 'mahasiswa_id', 'cv_id', 'topic', 'goal',
        'proposed_schedule', 'status', 'response_message', 'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'proposed_schedule' => 'datetime',
            'responded_at' => 'datetime',
        ];
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(MentorProfile::class, 'mentor_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function cv(): BelongsTo
    {
        return $this->belongsTo(TalentDocument::class, 'cv_id');
    }
}
