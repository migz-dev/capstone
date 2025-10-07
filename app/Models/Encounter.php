<?php

namespace App\Models;

use App\Models\Concerns\OwnedByFaculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Encounter extends Model
{
    use OwnedByFaculty;

    /** DB table */
    protected $table = 'chartings_encounters';

    /** Mass-assignable */
protected $fillable = [
  'patient_id','faculty_id','unit',
  'admission_dt','discharge_dt','started_at','ended_at',
  'attending_md','remarks',
];
    /** Casts */
    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    /** Convenience computed text like: “MS Ward · Enc#123 · Dela Cruz, Juan” */
    protected $appends = ['label'];

    /* --------------------------------- *
     | Relationships
     * --------------------------------- */

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function vitals(): HasMany
    {
        return $this->hasMany(Vital::class, 'encounter_id');
    }

    public function nursesNotes(): HasMany
    {
        return $this->hasMany(NursesNote::class, 'encounter_id');
    }

    public function marRecords(): HasMany
    {
        return $this->hasMany(MarRecord::class, 'encounter_id');
    }

    public function intakeOutputs(): HasMany
    {
        return $this->hasMany(IntakeOutput::class, 'encounter_id');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(TreatmentRecord::class, 'encounter_id');
    }

    public function ncpPlans(): HasMany
    {
        return $this->hasMany(NcpPlan::class, 'encounter_id');
    }

    /* --------------------------------- *
     | Scopes
     * --------------------------------- */

    /** Only currently active (no end time) */
    public function scopeActive($q)
    {
        return $q->whereNull('ended_at');
    }

    /** Filter by window (uses started_at) */
    public function scopeBetween($q, $from = null, $to = null)
    {
        if ($from) $q->where('started_at', '>=', $from);
        if ($to)   $q->where('started_at', '<=', $to);
        return $q;
    }

    /** Simple search by unit or patient name */
    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;

        return $q->where(function ($qq) use ($term) {
            $qq->where('unit', 'like', "%{$term}%")
               ->orWhereHas('patient', function ($qp) use ($term) {
                   $qp->where('last_name', 'like', "%{$term}%")
                      ->orWhere('first_name', 'like', "%{$term}%");
               });
        });
    }

    /* --------------------------------- *
     | Accessors
     * --------------------------------- */

    public function getLabelAttribute(): string
    {
        $parts = [];
        if ($this->unit) {
            $parts[] = $this->unit;
        }
        $parts[] = 'Enc#' . $this->id;

        // lazy-load is fine here; if not needed, eager load in controller with ->with('patient')
        $patient = $this->patient;
        if ($patient && ($patient->last_name || $patient->first_name)) {
            $parts[] = trim(($patient->last_name ?? '') . ', ' . ($patient->first_name ?? ''), ', ');
        }

        return implode(' · ', $parts);
    }
}
