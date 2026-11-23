<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentDocument extends Model
{
    public const TYPES = ['cv' => 'Curriculum Vitae', 'portofolio' => 'Portofolio', 'sertifikat' => 'Sertifikat'];

    protected $fillable = ['talent_profile_id', 'type', 'file_name', 'file_path'];

    public function talentProfile(): BelongsTo
    {
        return $this->belongsTo(TalentProfile::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }
}
