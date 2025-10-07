<?php
// app/Models/NursingCarePlan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NursingCarePlan extends Model
{
    protected $table = 'chartings_ncp';

    protected $fillable = [
        'faculty_id',
        'encounter_id',     // nullable, optional link
        'patient_name',     // free-typed label
        'noted_at',         // when this plan was made/updated
        // NANDA Dx
        'dx_primary',
        'dx_related_to',
        'dx_as_evidenced_by',
        // Goals
        'goal_short',
        'goal_long',
        // Interventions & Evaluation
        'interventions',
        'evaluation',
        // optional
        'remarks',
    ];

    protected $casts = [
        'noted_at' => 'datetime',
    ];

    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id');
    }

    /** Scope: only plans owned by logged-in CI */
    public function scopeOwned($q)
    {
        return $q->where('faculty_id', auth('faculty')->id());
    }

    /** Tiny preview for index tables */
    public function getPreviewAttribute(): string
    {
        $text = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([
            $this->dx_primary,
            $this->goal_short,
            $this->interventions,
        ]))));
        return mb_strimwidth($text, 0, 140, '…');
    }
}
