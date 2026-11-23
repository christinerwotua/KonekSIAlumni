<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    public const STATUS_LABELS = ['pending' => 'Menunggu', 'accepted' => 'Diterima', 'rejected' => 'Ditolak'];

    protected $fillable = ['job_referral_id', 'mahasiswa_id', 'cv_id', 'message', 'status'];

    public function jobReferral(): BelongsTo
    {
        return $this->belongsTo(JobReferral::class);
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
